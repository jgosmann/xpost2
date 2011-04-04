<?php

class WpFetcher {

    const tagsDir = 'tags';

    private $wpSvnUrl, $tagsUrl;
    private $svnDelegate;
    private $fsDelegate;

    public function __construct(SvnDelegate $svnDelegate,
        FsDelegate $fsDelegate, $wpSvnUrl = 'https://core.svn.wordpress.org/') {

        $this->wpSvnUrl = $wpSvnUrl;

        $this->svnDelegate = $svnDelegate;
        $this->fsDelegate = $fsDelegate;

        $this->tagsUrl = joinPaths($this->wpSvnUrl, self::tagsDir);
    }

    public function getLatestVersion() {
        $listing = $this->svnDelegate->listRepo($this->tagsUrl);
        return end($listing);
    }

    public function fetchVersion($version, $target) {
        $repo = joinPaths($this->tagsUrl, $version);
        if ($this->fsDelegate->fileExists(joinPaths($target, '.svn'))) {
            $this->svnDelegate->switchRepo($repo, $target);
        } else {
            $this->svnDelegate->checkout($repo, $target);
        }

        $this->svnDelegate->update($target);
    }
}
?>
