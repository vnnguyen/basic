<?php
//Image fetching and caching
define ('MAX_FILE_SIZE', 20971520); // 20MB
define ('FILE_CACHE_TIME_BETWEEN_CLEANS', 8640000);	// How often the cache is cleaned 100 days
define ('FILE_CACHE_MAX_FILE_AGE', 8640000);				// How old does a file have to be to be deleted from the cache
// define ('FILE_CACHE_DIRECTORY', './tmp/cache');				// Directory where images are cached. Left blank it will use the system temporary directory (which is better for security)