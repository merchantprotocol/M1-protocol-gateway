1. Make sure that PHP compilation function is disabled. This procedure was described in the installation topic (see 2nd step).

2. Make sure that files and database additional caching tools are disabled (e.g. APC cache).

3. Back up Magento files and database to be able to revert changes in a couple of minutes in case of possible problems.

4. Enable Magento error logs: System > Configuration > Advanced > Developer > Log Settings, Set Enabled to Yes. You can find errors information in files: var/log/system.log and var/log/exception.log (the file paths are related to the document root).

5. Open file app/etc/modules/MP_Gateway.xml (the file path is related to the document root)

6. Replace
`<active>true</active>`

with
`<active>false</active>`

Note, you can find the list of modules that the current module depends on under the next XML node: config > modules > depends

7. Run MySQL queries from the file: sql/mp_gateway/uninstall.sql

Note, if database tables are prefixed then the file should be run instead: sql/mp_gateway/prefixed_uninstall.sql. Replace [prefix] with your tables prefix.

8. Navigate to System > Cache Management. Press Flush Cache Storage button.

9. If the cache flushing fails then let us know error messages from the logs.

10. Check if extension isn’t listed in Magento anymore: System > Configuration > Advanced > Advanced. The module should be listed there.

11. Navigate to System > Index Management. Click Select All to select all indexes types and press Submit button.