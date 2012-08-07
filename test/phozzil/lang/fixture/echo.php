<?php

while (!feof(STDIN)) {
    $line = fgets(STDIN);
    fwrite(STDOUT, $line);
    fflush(STDOUT);
    fwrite(STDERR, $line);
    fflush(STDERR);
}
