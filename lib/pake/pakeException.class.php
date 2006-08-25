<?php

/*
 * This file is part of the pake package.
 * (c) 2004, 2005 Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * pakeException is the base class for all pake related exceptions and
 * provides an additional method for printing up a detailed view of an
 * exception.
 *
 * @package    pake
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class pakeException extends Exception
{
  function render($e)
  {
    $title = '  ['.get_class($e).']  ';
    $message = '  '.$e->getMessage().'  ';
    $len = max(strlen($message), strlen($title));
    $messages = array(
      str_repeat(' ', $len),
      $title.str_repeat(' ', $len - strlen($title)),
      $message.str_repeat(' ', $len - strlen($message)),
      str_repeat(' ', $len),
    );

    echo "\n";
    foreach ($messages as $message)
    {
      echo pakeColor::colorize($message, 'ERROR')."\n";
    }
    echo "\n";

    $pake = pakeApp::get_instance();

    if ($pake->get_trace())
    {
      echo "exception trace:\n";

      $trace = $this->trace($e);
      for ($i = 0, $count = count($trace); $i < $count; $i++)
      {
        $class = (isset($trace[$i]['class']) ? $trace[$i]['class'] : '');
        $type = (isset($trace[$i]['type']) ? $trace[$i]['type'] : '');
        $function = $trace[$i]['function'];
        $file = isset($trace[$i]['file']) ? $trace[$i]['file'] : 'n/a';
        $line = isset($trace[$i]['line']) ? $trace[$i]['line'] : 'n/a';

        echo sprintf(" %s%s%s at %s:%s\n", $class, $type, $function, pakeColor::colorize($file, 'INFO'), pakeColor::colorize($line, 'INFO'));
      }
    }

    echo "\n";
  }

  function trace($exception)
  {
    // exception related properties
    $trace = $exception->getTrace();
    array_unshift($trace, array(
      'function' => '',
      'file'     => ($exception->getFile() != null) ? $exception->getFile() : 'n/a',
      'line'     => ($exception->getLine() != null) ? $exception->getLine() : 'n/a',
      'args'     => array(),
    ));

    return $trace;
  }
}