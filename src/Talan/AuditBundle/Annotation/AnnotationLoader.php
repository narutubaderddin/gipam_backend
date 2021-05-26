<?php

namespace App\Talan\AuditBundle\Annotation;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class AnnotationLoader
{

    /**
     * @var AnnotationReader
     */
    private $reader;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->reader = new AnnotationReader();
    }

    public function load(): array
    {
        $configuration = ['classes' => array(), 'ignored' => array()];

        $finder = Finder::create()
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->ignoreUnreadableDirs(true)
            ->in([__DIR__ . '/../../../Entity'])
            ->exclude([])
            ->name('*.php');

        foreach ($finder as $file) {
            $classes = self::findClasses($file->getPathname());
            foreach ($classes as $class) {
                if ($this->isAuditable($class)) {
                    if($this->isAbstractClass($class)){
                        $childrenClass = $this->getChildrenClass($class);
                        foreach ($childrenClass as $class){
                            $configuration['classes'][] = $class;
                            $configuration['ignored'][$class] = $this->getIgnoredFields($class);
                        }
                    }else{
                        $configuration['classes'][] = $class;
                        $configuration['ignored'][$class] = $this->getIgnoredFields($class);
                    }
                }
            }
        }
        return $configuration;
    }

    /**
     * Extract the classes in the given file.
     *
     * This has been copied from https://github.com/composer/composer/blob/bfed974ae969635e622c4844e5e69526d8459baf/src/Composer/Autoload/ClassMapGenerator.php#L120-L214
     *
     * @param string $path The file to check
     *
     * @return array             The found classes
     *
     * @throws \RuntimeException
     */
    private static function findClasses($path)
    {
        // Use @ here instead of Silencer to actively suppress 'unhelpful' output
        // @link https://github.com/composer/composer/pull/4886
        $contents = @php_strip_whitespace($path);
        if (!$contents) {
            if (!file_exists($path)) {
                $message = 'File at "%s" does not exist, check your classmap definitions';
            } elseif (!is_readable($path)) {
                $message = 'File at "%s" is not readable, check its permissions';
            } elseif ('' === trim(file_get_contents($path))) {
                // The input file was really empty and thus contains no classes
                return [];
            } else {
                $message = 'File at "%s" could not be parsed as PHP, it may be binary or corrupted';
            }

            $error = error_get_last();
            if (isset($error['message'])) {
                $message .= PHP_EOL . 'The following message may be helpful:' . PHP_EOL . $error['message'];
            }

            throw new \RuntimeException(sprintf($message, $path));
        }

        // return early if there is no chance of matching anything in this file
        if (!preg_match('{\b(?:class|interface|trait|enum)\s}i', $contents)) {
            return [];
        }

        // strip heredocs/nowdocs
        $contents = preg_replace('{<<<\s*(\'?)(\w+)\\1(?:\r\n|\n|\r)(?:.*?)(?:\r\n|\n|\r)\\2(?=\r\n|\n|\r|;)}s', 'null', $contents);
        // strip strings
        $contents = preg_replace('{"[^"\\\\]*+(\\\\.[^"\\\\]*+)*+"|\'[^\'\\\\]*+(\\\\.[^\'\\\\]*+)*+\'}s', 'null', $contents);
        // strip leading non-php code if needed
        if ('<?' !== substr($contents, 0, 2)) {
            $contents = preg_replace('{^.+?<\?}s', '<?', $contents, 1, $replacements);
            if (0 === $replacements) {
                return [];
            }
        }

        // strip non-php blocks in the file
        $contents = preg_replace('{\?>.+<\?}s', '?><?', $contents);
        // strip trailing non-php code if needed
        $pos = strrpos($contents, '?>');
        if (false !== $pos && false === strpos(substr($contents, $pos), '<?')) {
            $contents = substr($contents, 0, $pos);
        }

        preg_match_all('{
            (?:
                 \b(?<![\$:>])(?P<type>class|interface|trait|enum) \s++ (?P<name>[a-zA-Z_\x7f-\xff:][a-zA-Z0-9_\x7f-\xff:\-]*+)
               | \b(?<![\$:>])(?P<ns>namespace) (?P<nsname>\s++[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*+(?:\s*+\\\\\s*+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*+)*+)? \s*+ [\{;]
            )
        }ix', $contents, $matches);

        $classes = [];
        $namespace = '';

        for ($i = 0, $len = count($matches['type']); $i < $len; $i++) {
            if (!empty($matches['ns'][$i])) {
                $namespace = str_replace([' ', "\t", "\r", "\n"], '', $matches['nsname'][$i]) . '\\';
            } else {
                $name = $matches['name'][$i];
                // skip anon classes extending/implementing
                if ('extends' === $name || 'implements' === $name) {
                    continue;
                }

                if (':' === $name[0]) {
                    // This is an XHP class, https://github.com/facebook/xhp
                    $name = 'xhp' . substr(str_replace(['-', ':'], ['_', '__'], $name), 1);
                } elseif ('enum' === $matches['type'][$i]) {
                    // In Hack, something like:
                    //   enum Foo: int { HERP = '123'; }
                    // The regex above captures the colon, which isn't part of
                    // the class name.
                    $name = rtrim($name, ':');
                }

                $classes[] = ltrim($namespace . $name, '\\');
            }
        }

        return $classes;
    }

    public function isAuditable($class): bool
    {
        $annotationReader = new AnnotationReader();
        $reflectionClass = new ReflectionClass($class);
        return $annotationReader->getClassAnnotation($reflectionClass, Auditable::class) !== null;
    }

    public function isAbstractClass($class): bool
    {
        $annotationReader = new AnnotationReader();
        $reflectionClass = new ReflectionClass($class);
        return $annotationReader->getClassAnnotation($reflectionClass, AbstractClass::class) !== null;
    }
    public function getChildrenClass($class):array
    {
        $annotationReader = new AnnotationReader();
        $reflectionClass = new ReflectionClass($class);
        $classAnnotation=$annotationReader->getClassAnnotation($reflectionClass, AbstractClass::class);
        $children=[];
        if($classAnnotation!=null){
            $children = $classAnnotation->children;
        }
        return $children;
    }
    public function getIgnoredFields($class): array
    {
        $reflection = new ReflectionClass($class);
        $properties = [];
        foreach ($reflection->getProperties() as $property) {
            if (null != $this->reader->getPropertyAnnotation($property, Ignore::class)) {

                if (((null != $annotationData = $this->reader->getPropertyAnnotation($property, Column::class))
                        || (null != $annotationData = $this->reader->getPropertyAnnotation($property, JoinColumn::class)))
                    &&
                    (property_exists($annotationData, 'name'))
                ) {
                    $key = $annotationData->name;
                } else {
                    $key = $property->getName();
                }


                array_push($properties, $key);

            }
        }
        return $properties;
    }
}
