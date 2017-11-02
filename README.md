# Merchant Protocol Gateway

Magento extension for accepting credit card payments using the Merchant Protocol Payment Gateway.

## Version Control

This change log and release versions will be managed according to [keepachangelog.com](http://keepachangelog.com/) and [Semantic Versioning 2.0.0](http://semver.org/).  **Major.Features.Improvements.Fixes**

## Magento Compatible Versions

* *Magento Enterprise Edition* **1.13.x** ~ **1.14.x**
* *Magento Community Edition* **1.6.x** ~ **1.9.x**

## System Requirements

* PHP 5.4 >

## Installation

Extension files are located in the following directories:

- app/code/community/MP/Gateway
- app/design/adminhtml/default/default/layout/mp_gateway.xml
- app/design/adminhtml/default/default/template/mp_gateway
- app/design/frontend/base/default/layout/mp_gateway.xml
- app/design/frontend/base/default/template/mp_gateway
- app/etc/modules/MP_Gateway.xml
- media/mp_gateway
- skin/adminhtml/default/default/mp_debug
- skin/adminhtml/default/default/mp_gateway
- skin/frontend/base/default/mp_debug
- skin/frontend/base/default/mp_gateway

### Installation with [Modman](https://github.com/colinmollenhour/modman)

In the Magento root folder start a modman repository:

```bash
modman init
```

Clone the module directly from github repository:

```bash
modman clone https://github.com/merchantprotocol/M1-core.git
modman clone https://github.com/merchantprotocol/M1-protocol-gateway.git
```

### Manual installation

Download the <a href="https://github.com/merchantprotocol/M1-protocol-gateway/archive/master.zip">zip file</a> and copy the entire contents of the folder into the Magento root directory. For example:

```bash
unzip ~/Downloads/M1-protocol-gateway-master.zip
cp -R ~/Downloads/M1-protocol-gateway-master/* /var/www/html
```

### Uninstallation

```bash
php shell/mp/core.php --action uninstall --extension MP_Gateway --modman M1-protocol-gateway
```

## Contributing

1. Create a fork!
2. Create a branch for the features: `git checkout -b my-new-feature`
3. Make commit yours changes: `git commit -am 'Add some feature'`
4. Give a push to branch: `git push origin my-new-feature`
5. Create a pull request

## Credits

Author||Version
--- | --- | ---
**Merchant Protocol** | david@merchantprotocol.com | `1.3.1.0`

## NOTICE OF LICENSE

	This source file is subject to the Merchant Protocol Commercial License (MPCL 1.0)
	that is bundled with this package in the file LICENSE.md.
	It is also available through the world-wide-web at this URL:
	https://merchantprotocol.com/commercial-license/
	If you did not receive a copy of the license and are unable to
	obtain it through the world-wide-web, please send an email
	to info@merchantprotocol.com so we can send you a copy immediately.
        
	DISCLAIMER
        
	Do not edit or add to this file if you wish to upgrade to newer
	versions in the future. If you wish to customize the extension for your
	needs please refer to http://www.merchantprotocol.com for more information.
	
	Copyright (c) 2006-2016 Merchant Protocol LLC. and affiliates (https://merchantprotocol.com/)
	https://merchantprotocol.com/commercial-license/  Merchant Protocol Commercial License (MPCL 1.0)
	
