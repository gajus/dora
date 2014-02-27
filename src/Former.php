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
        $this->logger = $logger;
    }

    public function form (array $default_data = null, array $input = null) {
		$form = new Form($default_data, $input, $this->logger);

		return new Dress($form);
	}
}