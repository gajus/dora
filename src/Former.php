<?php
namespace Gajus\Dora;

/**
 * Factory for the DI container.
 * 
 * @link https://github.com/gajus/dora for the canonical source repository
 * @license https://github.com/gajus/dora/blob/master/LICENSE BSD 3-Clause
 */
class Former {
	private
        /**
         * @var Psr\Log\LoggerInterface
         */
        $logger;

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     */
    public function __construct (\Psr\Log\LoggerInterface $logger = null) {
        if ($logger === null) {
            $logger = new \Psr\Log\NullLogger();
        }
        
        $this->logger = $logger;
    }

    public function form (array $default_data = null) {
		$form = new Form($default_data, $this->logger);

		return new Dress($form);
	}
}