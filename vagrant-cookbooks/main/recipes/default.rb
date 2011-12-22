#
# Cookbook Name:: main
# Recipe:: default
#
# Copyright 2011, Syncapse
#
# All rights reserved - Do Not Redistribute
#

###################################################################
# Apt config
#
# Just include the apt recipe so that we know sources are updated
###################################################################
include_recipe "apt"

###################################################################
# PHP
###################################################################
include_recipe "php"

file = File.new( "/var/log/php.log", "w" )
file.chmod( 0644 )
file.chown(33, 33)
file.close

package "php5-curl" do
  action :install
end

package "php5-mysql" do
  action :install
end

package "php5-xdebug" do
  action :install
end

php_pear_channel "pear.phpunit.de" do
  action :discover
end

php_pear "PHPUnit" do
  action :install
end

###################################################################
# Apache
###################################################################
include_recipe "apache2"
include_recipe "apache2::mod_php5"
include_recipe "apache2::mod_rewrite"

web_app "#{node[:web][:vhost_name]}" do
  server_name "#{node[:web][:vhost_name]}"
  docroot "#{node[:web][:docroot]}"
end

###################################################################
# MySQL
###################################################################
include_recipe "mysql"
include_recipe "mysql::server"

bash "create initial database" do
  # Check that the database doesn't already exist
  not_if("/usr/bin/mysql -uroot -p#{node[:mysql][:server_root_password]} -e'show databases' | grep #{node[:mysql][:db_name]}")

  # a heredoc of the code to execute, note the node hash is created from the JSON file
  code <<-HEREDOC
  mysql -uroot -p#{node[:mysql][:server_root_password]} -e 'create database #{node[:mysql][:db_name]}'
  HEREDOC
end

###################################################################
# Node.js
###################################################################
package "nodejs" do
  action :install
end

###################################################################
# Utility packages
###################################################################
package "vim" do
  action :install
end

###################################################################
# Replace PHP config with our templates
###################################################################
template "/etc/php5/apache2/php.ini" do
  source "php.ini.erb"
  mode "0644"
  notifies :restart, resources(:service => "apache2")
end

template "/etc/php5/cli/php.ini" do
  source "php.ini.erb"
  mode "0644"
end