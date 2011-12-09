#
# Cookbook Name:: main
# Recipe:: default
#
# Copyright 2011, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#

include_recipe "apache2"
include_recipe "apache2::mod_rewrite"

web_app "remod" do
  server_name "remod.dev"
  docroot "/srv/http"
end

include_recipe "apache2::mod_php5"

file = File.new( "/var/log/php.log", "w" )
file.chmod( 0644 )
file.chown(33, 33)
file.close

template "/etc/php/php.ini" do
  source "php.ini.erb"
  notifies :restart, resources(:service => "apache2")
end


package "nodejs" do
  action :install
end

package "vim" do
  action :install
end

package "mysql" do
  action :install
end

service "mysqld" do
  action [:enable, :start]
end