def value_for_platform ( one) 
  puts one[[ "centos", "redhat", "suse", "fedora", "scientific", "amazon"]]
end

 value_for_platform({
    [ "centos", "redhat", "suse", "fedora", "scientific", "amazon"] => { "default" => "mysql" },
    [ "arch" ] => { "default" => "mysql" },
    "default" => "mysql-client"
}
  )


