#
# Cookbook Name:: main
# Recipe:: default
#
# Copyright 2011, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
execute "Sync pacman" do
  command "pacman -Sy"
  action :run
end

include_recipe "apache2"
include_recipe "apache2::mod_rewrite"

web_app "remod" do
  server_name "remod.dev"
  docroot "/srv/http/remod/src"
end

include_recipe "apache2::mod_php5"

file = File.new( "/var/log/php.log", "w" )
file.chmod( 0644 )
file.chown(33, 33)
file.close

template "/etc/php/php.ini" do
  source "php.ini.erb"
  mode "0644"
  notifies :restart, resources(:service => "apache2")
end

package "php-pear" do
  action :install
end

package "xdebug" do
  action :install
end

execute "Upgrade PEAR" do
  command "pear upgrade PEAR && touch /home/vagrant/.upgraded-pear"

  not_if do
    File.exists?("/home/vagrant/.upgraded-pear")
  end

  action :run
end

execute "Install PHPUnit" do
  command "pear config-set auto_discover 1 && pear install pear.phpunit.de/PHPUnit && touch /home/vagrant/.phpunit"

  not_if do
    File.exists?("/home/vagrant/.phpunit")
  end

  action :run
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