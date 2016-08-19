<?php

/*
 * Copyright (C) 2015 Ashley Marando
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace Marando\Process;

use SplFileObject;

/**
 * Manages external processes.
 *
 * @property SplFileObject $log     File with output of the process
 * @property string        $command Process command
 * @property string        $pid     Process PID
 * @property int           $runtime Seconds process took to run
 */
class Process
{

    //--------------------------------------------------------------------------
    // Constructors
    //--------------------------------------------------------------------------

    /**
     * Creates a new process.
     *
     * @param             $command The command to run.
     * @param null|string $log     Optional path to direct the process output.
     */
    function __construct($command, $log = null)
    {
        $this->command = $command;

        if ($log) {
            // Log was specified, so create new file object for it.
            $this->log = new SplFileObject($log, 'a+');
        }
    }

    //--------------------------------------------------------------------------
    // Properties
    //--------------------------------------------------------------------------

    /**
     * Process command
     *
     * @var string
     */
    private $command;

    /**
     * Process id
     *
     * @var int
     */
    private $pid;

    /**
     * Process output file
     *
     * @var SplFileObject
     */
    private $log;

    function __get($name)
    {
        switch ($name) {
            case 'log':
            case 'pid':
                return $this->{$name};
        }
    }

    //--------------------------------------------------------------------------
    // Functions
    //--------------------------------------------------------------------------

    /**
     * Starts the process.
     */
    public function start()
    {
        if ($this->isRunning()) {
            return false;
        }

        // Should output be directed to a file?
        $file = $this->log ? $this->log->getRealPath() : '/dev/null';

        // Execute the command and set it's process id.
        exec("{$this->command} > {$file} 2>&1 & echo $!", $output);
        $this->pid = (int)$output[0];
    }

    /**
     * Kills the process.
     */
    public function kill()
    {
        exec("kill {$this->pid}");
    }

    /**
     * Returns true if the process is running, false if it is not.
     *
     * @return bool
     */
    public function isRunning()
    {
        if ($this->pid) {
            exec("ps -p {$this->pid}", $output);

            return count($output) > 1 ? true : false;
        } else {
            return false;
        }
    }

    /**
     * Waits until the process is no longer running.
     */
    public function wait()
    {
        while ($this->isRunning()) {
            ;
        }
    }

}
