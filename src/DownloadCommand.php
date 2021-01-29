<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

use RuntimeException;

class DownloadCommand extends Command
{
    /** @var string */
    private $destPath;

    /**
     * @return void
     */
    public function __construct(WebDavClientOptions $options, string $srcUri, string $destPath)
    {
        parent::__construct($options, 'GET', $srcUri);
        $this->destPath = $destPath;
    }

    /**
     * @throws RuntimeException
     */
    protected function postRequest(): void
    {
        if (\file_put_contents($this->destPath, parent::getResponse()->getBody()->getContents()) === false) {
            throw new RuntimeException('Failed to create file (' . $this->destPath . ')');
        }
    }
}
