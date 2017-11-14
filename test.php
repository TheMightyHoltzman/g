#!/usr/bin/php
<?php

const PROD  = 0;
const DEBUG = 1;

/**
 * Created by PhpStorm.
 * User: heiko
 * Date: 31.07.17
 * Time: 09:00
 */

if (sizeof($argv) !== 2) {
    echo "ERROR: Invalid parameter nr - only one parameter (sample-size) is allowed" . PHP_EOL;
    return;
}
elseif (!filter_var($argv[1], FILTER_VALIDATE_INT)) {
    echo "ERROR: Supplied sample size must be of type int" . PHP_EOL;
    return;
}

$sampleSize  = (int)$argv[1];
$sampler     = new StreamSampler($sampleSize, DEBUG);
//$sample      = new StreamSampler($sampleSize, PROD);
$sampler->work();

class StreamSampler {
  /**
  * nr of chars to sample
  */
  private $sampleSize;

  /**
  * nr of chars already sampled
  */
  private $sampled;

  /**
   * the current char to be processed
   */
  private $currentChar;

  /**
  * the actual sample as an array of chars
  */
  private $sample;

  /**
   * 1 = DEBUG, 0 = PROD
   */
  private $mode;

  public function __construct($sampleSize, $mode = PROD) {
    $this->sampleSize  = $sampleSize;
    $this->mode        = $mode;
    $this->currentChar = null;
    $this->sampled     = 0;
    $this->sample      = [];
  }

  /**
   *
   */
  public function work() {
    while($line = fgets(STDIN)){
        $this->processLine($line);
    }
    $sampleString = implode($this->sample);
    echo "Random Sample: $sampleString" . PHP_EOL;
  }

  public function processLine($line) {
    $lineLength = strlen($line);
    for ($i = 0; $i < $lineLength; $i++) {
      $this->currentChar = $line[$i];
      ++$this->sampled;
      $this->processChar();
    }
  }

  private function processChar() {
      // if we dont have a full sample yet, we add unconditionally
      if (sizeof($this->sample) < $this->sampleSize) {
        $this->addCurrentChar();
        return;
      }

    $addProb     = $this->computeAddProb();
    $doAdd       = mt_rand(0, 1) <= $addProb;
    $charAdded   = null;
    $charRemoved = null;

    if ($doAdd) {
      $charRemoved = $this->removeRandOne();
      $charAdded   = $this->addCurrentChar();
    }
  }

  private function computeAddProb() {
    // the probabilty of an element being in a a subset of (n choose k) is:
    return 1/2;
  }

  private function addCurrentChar() {
    $this->sample[] = $this->currentChar;
    return $this->currentChar;
  }

  private function removeRandOne() {
    $key = array_rand($this->sample);
    unset($this->sample[$key]);

    return $key;
  }
}

?>
