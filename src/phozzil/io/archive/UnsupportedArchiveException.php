<?php

namespace phozzil\io\archive;

use phozzil\io\IOException;

/**
 * 対応していない圧縮形式を指定された場合に投げられる例外です。
 */
class UnsupportedArchiveException extends IOException {}