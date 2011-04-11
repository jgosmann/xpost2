<?php

interface SvnDelegate {
    public function listRepo($repo);
    public function checkout($repo, $target);
    public function switchRepo($repo, $target);
    public function update($target);
}

?>
