<?php

class CliSvnDelegate implements SvnDelegate {

    private $svnPathEscaped;

    public function __construct($svnPath = 'svn') {
        $this->svnPathEscaped = escapeshellarg($svnPath);
    }

    public function listRepo($repo) {
        return explode('\n',
            $this->execSvnCommand('list', escapeshellarg($repo)));
    }

    public function checkout($repo, $target) {
        $this->execSvnCommand('checkout', escapeshellarg($repo),
            escapeshellarg($target));
    }

    public function switchRepo($repo, $target) {
        $this->execSvnCommand('switch', escapeshellarg($repo),
            escapeshellarg($target));
    }

    public function update($target) {
        $this->execSvnCommand('update', escapeshellarg($target));
    }

    private function execSvnCommand($command /*, ... */) {
        $args = implode(' ', func_get_args());
        $returnValue = 0;
        $output = system("$this->svnPathEscaped $args", $returnValue);
        if ($returnValue != 0) {
            throw new SvnException(func_get_args(), $output);
        }
        return $output;
    }
}

?>
