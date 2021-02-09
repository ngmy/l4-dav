<?php

declare(strict_types=1);

namespace Ngmy\L4Dav;

class CandidateUrl extends Url
{
    protected function validate(): void
    {
        // no-op
    }

    public function isRelativeUrl(): bool
    {
        return empty($this->uri->getScheme())
            && empty($this->uri->getAuthority());
    }

    public function isFullUrl(): bool
    {
        return \in_array($this->uri->getScheme(), ['http', 'https'])
            && !empty($this->uri->getAuthority())
            && (!$this->hasPath() || $this->hasPathWithLeadingSlash());
    }
}
