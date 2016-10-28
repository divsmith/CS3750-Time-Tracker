<?php

namespace Peridot\Reporter\CodeCoverage;

use PHP_CodeCoverage_Report_Text;

/**
 * Class TextCodeCoverageReporter
 * @package Peridot\Reporter\CodeCoverage
 */
class TextCodeCoverageReporter extends AbstractCodeCoverageReporter
{
    /**
     * @var boolean
     */
    protected $reportPath = false;

    /**
     * Create the desired code coverage reporter.
     *
     * @return \PHP_CodeCoverage_Report_XML
     */
    protected function createCoverageReporter()
    {
        return new PHP_CodeCoverage_Report_Text(50, 90, true, false);
    }
}
