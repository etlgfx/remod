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
include_recipe "apache2::mod_php5"

web_app "remod" do
  server_name "remod.dev"
  docroot "/srv/http"
end

# include_recipe "mysql::mysql_client"

package "nodejs" do
  action :install
end

package "mysql" do
  action :install
end

service "mysqld" do
  action [:enable, :start]
end