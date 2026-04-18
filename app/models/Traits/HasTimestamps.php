<?php

trait HasTimestamps {

    protected function now() {
        return date("Y-m-d H:i:s");
    }
}