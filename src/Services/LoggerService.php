<?php

namespace App\Services;


use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\HttpKernel\KernelInterface;

class LoggerService
{
    protected $logger;
    /**
     * @var KernelInterface
     */
    private $kernel;

    private $isInitialized;

    /**
     * LoggerService constructor.
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->isInitialized =false;
    }

    public function init($class, $level =Logger::DEBUG){
        $className = $class;
        if (is_object($class)) {
            $classNameExploded = explode('\\', get_class($class));
            $className = end($classNameExploded);
        }
        $this->logger = new Logger($className);
        try {
            $this->logger->pushHandler(
                new StreamHandler($this->kernel->getLogDir() . '/' . $className . '.' . $this->kernel->getEnvironment() . '.log', $level)
            );
            $this->isInitialized = true;
        } catch (Exception $e) {

        }
    }
    public function getLogger()
    {
        if($this->isInitialized){
            return $this->logger;
        }else{
            throw new Exception('logger is not initialized !');
        }

    }

    public function setLogger(Logger $logger)
    {
        if($this->isInitialized){
            $this->logger = $logger;
            return $this;
        }else{
            throw new Exception('logger is not initialized !');
        }
    }

    /**
     * Adds a log record at the DEBUG level.
     *
     * @param  string $message The log message
     * @param  array $context The log context
     * @return Boolean Whether the record has been processed
     * @throws Exception
     */
    public function addDebug($message, array $context = array())
    {
        if($this->isInitialized){
            return $this->getLogger()->addDebug($message, $context);
        }else{
            throw new Exception('logger is not initialized !');
        }

    }

    /**
     * Adds a log record at the INFO level.
     *
     * @param  string $message The log message
     * @param  array $context The log context
     * @return Boolean Whether the record has been processed
     * @throws Exception
     */
    public function addInfo($message, array $context = array())
    {
        if($this->isInitialized){
            return $this->getLogger()->addInfo($message, $context);
        }else{
            throw new Exception('logger is not initialized !');
        }

    }

    /**
     * Adds a log record at the NOTICE level.
     *
     * @param  string $message The log message
     * @param  array $context The log context
     * @return Boolean Whether the record has been processed
     * @throws Exception
     */
    public function addNotice($message, array $context = array())
    {
        if($this->isInitialized){
            return $this->getLogger()->addNotice($message, $context);
        }else{
            throw new Exception('logger is not initialized !');
        }

    }

    /**
     * Adds a log record at the WARNING level.
     *
     * @param  string $message The log message
     * @param  array $context The log context
     * @return Boolean Whether the record has been processed
     * @throws Exception
     */
    public function addWarning($message, array $context = array())
    {
        if($this->isInitialized){
            return $this->getLogger()->addWarning($message, $context);
        }else{
            throw new Exception('logger is not initialized !');
        }

    }

    /**
     * Adds a log record at the ERROR level.
     *
     * @param  string $message The log message
     * @param  array $context The log context
     * @return Boolean Whether the record has been processed
     * @throws Exception
     */
    public function addError($message, array $context = array())
    {
        if($this->isInitialized){
            return $this->getLogger()->addError($message, $context);
        }else{
            throw new Exception('logger is not initialized !');
        }

    }

    /**
     * Adds a log record at the CRITICAL level.
     *
     * @param  string $message The log message
     * @param  array $context The log context
     * @return Boolean Whether the record has been processed
     * @throws Exception
     */
    public function addCritical($message, array $context = array())
    {
        if($this->isInitialized){
            return $this->getLogger()->addCritical($message, $context);
        }else{
            throw new Exception('logger is not initialized !');
        }

    }

    /**
     * Adds a log record at the ALERT level.
     *
     * @param  string $message The log message
     * @param  array $context The log context
     * @return Boolean Whether the record has been processed
     * @throws Exception
     */
    public function addAlert($message, array $context = array())
    {
        if($this->isInitialized){
            return $this->getLogger()->addAlert($message, $context);
        }else{
            throw new Exception('logger is not initialized !');
        }

    }

    /**
     * Adds a log record at the EMERGENCY level.
     *
     * @param  string $message The log message
     * @param  array $context The log context
     * @return Boolean Whether the record has been processed
     * @throws Exception
     */
    public function addEmergency($message, array $context = array())
    {
        if($this->isInitialized){
            return $this->getLogger()->addEmergency($message, $context);
        }else{
            throw new Exception('logger is not initialized !');
        }

    }
    /**
     * Show the current memory usage during execution.
     */
    public function logMemoryUsage($message = '')
    {
        if($this->isInitialized){
            $mem_usage = memory_get_usage(true);
            $res = null;
            if ($mem_usage < 1024) {
                $res = $mem_usage . " bytes";
            } elseif ($mem_usage < 1048576) {
                $res = round($mem_usage / 1024, 2) . " kilobytes";
            } else {
                $res = round($mem_usage / 1048576, 2) . " megabytes";
            }
            $this->logger->addInfo("Memory usage: " . $message . ' => ' . $res);
        }else{
            throw new Exception('logger is not initialized !');
        }

    }

    /**
     * @param $endtime
     * @param $starttime
     * @param $message
     * @return mixed
     * @throws Exception
     * @codeCoverageIgnore
     */
    function formatPeriod($endtime, $starttime ,$message)
    {
        if($this->isInitialized){
            $duration = $endtime - $starttime;

            $hours = (int)($duration / 60 / 60);

            $minutes = (int)($duration / 60) - $hours * 60;

            $seconds = (int)$duration - $hours * 60 * 60 - $minutes * 60;

            $res =($hours == 0 ? "00" : $hours) . ":" . ($minutes == 0 ? "00" : ($minutes < 10 ? "0" . $minutes : $minutes)) . ":" . ($seconds == 0 ? "00" : ($seconds < 10 ? "0" . $seconds : $seconds));
            $this->logger->addInfo("Temp d'execution : " .$message.' => '.$res);
        }else{
            throw new Exception('logger is not initialized !');
        }

    }
}