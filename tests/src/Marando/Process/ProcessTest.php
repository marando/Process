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

namespace Marando\AstroDate;

use Marando\Process\Process;
use \PHPUnit_Framework_TestCase;

class ProcessTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests that a process that is not running returns false.
     */
    public function testIsRunningFalse()
    {
        $proc = new Process('ls');
        $proc->start();
        $this->assertFalse($proc->isRunning());
    }

    /**
     * Tests that a process that is running returns true.
     */
    public function testIsRunningTrue()
    {
        $proc = new Process('ping -c 2 localhost');
        $proc->start();
        $this->assertTrue($proc->isRunning());
    }

    /**
     * Tests that a killed process is no longer running.
     */
    public function testKill()
    {
        $proc = new Process('ping -c 4 localhost');
        $proc->start();
        $proc->kill();

        $this->assertFalse($proc->isRunning());
    }

    /**
     * Tests the PID exists and is numeric.
     */
    public function testPID()
    {
        $proc = new Process('ls');
        $proc->start();

        $this->assertInternalType('integer', $proc->pid);
    }

    /**
     * Tests that the output of a process is directed to a file if desired.
     */
    public function testOutput()
    {
        $proc = new Process('ping -c 1 localhost', 'out.log');
        $proc->start();

        while ($proc->isRunning()) {

        }

        $out = $proc->log->fread(100);
        unlink($proc->log->getRealPath());

        $this->assertContains('PING localhost', $out);
    }

}