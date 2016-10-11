<?php

namespace Saritasa\Tools;

/**
 * This class helps to measure execution time and memory usage of your script as well as
 * compare the performance of two or more code samples.
 */
class VS
{
    /**
     * Info about test callbacks.
     *
     * @var array
     */
    private $items = [];
    
    /**
     * The final result of all tests.
     *
     * @var array
     */
    private $values = [];
    
    /**
     * The number of test runs by default.
     *
     * @var int
     */
    private $defaultTestNumber = 0;
    
    /**
     * The current group of tests.
     *
     * @var string
     */
    private $group = '';
    
    /**
     * The measured value of a subsequent test.
     *
     * @var int
     */
    private $value = 0;
    
    /**
     * Constructor.
     *
     * @param int $defaultTestNumber The number of test runs by default.
     */
    public function __construct($defaultTestNumber = 10)
    {
        $this->defaultTestNumber = (int)$defaultTestNumber;
    }
    
    /**
     * Sets the group of tests.
     *
     * @param string $group
     * @return void
     */
    public function group($group)
    {
        $this->group = $group;
    }
    
    /**
     * Adds a test callback for measuring.
     *
     * @param string $name The entity title.
     * @param callable $func The test callback.
     * @param bool $pivot If it's TRUE, this test is considered as the main (will get 100% value) relative to other tests.
     * @return void     
     */
    public function add($name, callable $func, $pivot = false)
    {
        $this->items[] = [
            'name' => $name,
            'func' => $func,
            'pivot' => $pivot
        ];
    }
    
    /**
     * Removes all test callbacks.
     *
     * @return void
     */
    public function clean()
    {
        $this->items = [];
    }
    
    /**
     * Launches test callbacks to measure execution time.
     *
     * @param string $title The test case title.
     * @param bool $important Determines whether the test case should be marked as important.
     * @param int $testNumber The number of test runs. If it's not specified, the default number of test runs will be used.
     * @param bool $cleanAfterUsage Determines whether test callbacks should be removed after test.
     * @return void
     */
    public function time($title, $important = false, $testNumber = null, $cleanAfterUsage = true)
    {
        $this->vs($title, $important, $testNumber, $cleanAfterUsage, 'time');
    }
    
    /**
     * Launches test callbacks to measure memory usage.
     *
     * @param string $title The test case title.
     * @param bool $important Determines whether the test case should be marked as important.
     * @param int $testNumber The number of test runs. If it's not specified, the default number of test runs will be used.
     * @param bool $cleanAfterUsage Determines whether test callbacks should be removed after test.
     * @return void
     */
    public function space($title, $important = false, $testNumber = 1, $cleanAfterUsage = true)
    {
        $this->vs($title, $important, $testNumber, $cleanAfterUsage, 'space');
    }
    
    /**
     * Displays the measuring result.
     *
     * @param bool $sortByImportance Determines whether the important test cases are displayed first.
     * @return void
     */
    public function display($sortByImportance = true)
    {
        echo '<h1>PHP ' . PHP_VERSION . '</h1>';
        foreach ($this->values as $type => $groups)
        {
            if ($type == 'time')
            {
                echo '<h1>Execution Time</h1>';
            }
            else
            {
                echo '<h1>Memory Usage</h1>';
            }
            foreach ($groups as $group => $tests)
            {
                if ($sortByImportance)
                {
                    uasort($tests, function(array $a, array $b)
                    {
                        return (int)$b['important'] - (int)$a['important'];
                    });
                }
                echo '<h2>' . $group . '</h2><hr />';
                foreach ($tests as $title => $test)
                {
                    $items = $test['items'];
                    $pivotKey = $this->findPivotKey($items);
                    $pivotRatio = $items[$pivotKey]['value'];
                    echo '<h3>' . $title . ($test['important'] ? ' &#9733;' : '') . '</h3>';
                    echo '<table border="1" cellpadding="10">';
                    echo '<tr><th>Name</th><th>Ratio</th><th>';
                    echo 'Value (' . ($type == 'space' ? 'byte' : 'sec') . ')</th></tr>';
                    foreach ($items as $name => $item)
                    {
                        $ratio = $name == $pivotKey ? 100 : $item['value'] / $pivotRatio * 100;
                        echo '<tr>';
                        echo '<td>' . $name . '</td>';
                        echo '<td>' . number_format($ratio, 0) . '%</td>';
                        echo '<td>' . number_format($item['value'], is_int($item['value']) ? 0 : 7) . '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                }
            }
        }
    }
    
    /**
     * Performs measuring either execution time or memory usage.
     *
     * @param string $title The test case title.
     * @param bool $important Determines whether the test case should be marked as important.
     * @param int $testNumber The number of test runs. If it's not specified, the default number of test runs will be used.
     * @param bool $cleanAfterUsage Determines whether test callbacks should be removed after test.
     * @param string $type The action name (valid values are 'time' or 'space').
     * @return void
     */
    private function vs($title, $important, $testNumber, $cleanAfterUsage, $type)
    {
        if (count($this->items) < 2)
        {
            throw new \BadMethodCallException('You need to specify two or more callbacks to compare.');
        }
        $values = [];
        $start = $type . 'Start';
        $stop = $type . 'Stop';
        $tests = $testNumber !== null ? (int)$testNumber : $this->defaultTestNumber;
        gc_collect_cycles();
        gc_disable();
        while ($tests)
        {
            $items = $this->items;
            shuffle($items);
            foreach ($items as $item)
            {
                $this->{$start}();
                $res = call_user_func($item['func']);
                $values[$item['name']][] = $this->{$stop}();
                unset($res);
            }
            --$tests;
        }
        gc_enable();
        $items = [];
        foreach ($this->items as $item)
        {
            $name = $item['name'];
            $value = array_sum($values[$name]) / count($values[$name]);
            $items[$name] = [
                'value' => $value,
                'pivot' => $item['pivot']
            ];
        }
        $this->values[$type][$this->group][$title] = [
            'items' => $items,
            'important' => $important
        ];     
        if ($cleanAfterUsage)
        {
            $this->clean();
        }
    }
    
    /**
     * Searches the pivot test.
     * 
     * @param array $items
     * @return string
     */
    private function findPivotKey(array $items)
    {
        $key = false;
        foreach ($items as $name => $item)
        {
            if ($item['pivot'])
            {
                $key = $name;
            }
        }
        if ($key === false)
        {
            $key = key($items);
        }
        return $key;
    }
    
    /**
     * Starts to measure the execution time of a test callback.
     *
     * @return void
     */
    private function timeStart()
    {
        $this->value = microtime(true);
    }
    
    /**
     * Ends up to measure the execution time of a test callback.
     *
     * @return float
     */
    private function timeStop()
    {
        return microtime(true) - $this->value;
    }
    
    /**
     * Starts to measure the memory usage of a test callback.
     *
     * @return void
     */
    private function spaceStart()
    {
        $this->value = memory_get_usage();
    }
    
    /**
     * Ends up to measure the memory usage of a test callback.
     *
     * @return int
     */
    private function spaceStop()
    {
        return memory_get_usage() - $this->value;
    }
}