<?php
/**
 *
 * Little piece of PHP to make P2p_Cdn auto-loadable in PSR-0 compatible PHP autoloaders like
 * the Symfony Universal ClassLoader by Fabien Potencier. Since PSR-0 handles an underscore in
 * classnames (on the filesystem) as a slash, "P2p_Cdn.php" autoloaders will try to convert
 * the classname and path to "P2p\Cdn.php". This script will ensure autoloading with:
 *  - Namespace:       P2pcdn
 *  - Classname:       P2pCdn
 *  - Namespased:      \P2pcdn\P2pCdn
 *  - Autoload path:   ./namespaced
 *  - Converted path:  ./namespaced/P2pcdn\P2pCdn.php
 *
 */


namespace P2pcdn;
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'P2p-Cdn.php';

class P2pCdn extends \P2p_Cdn {}
