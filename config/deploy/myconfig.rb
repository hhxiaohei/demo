set :staging_servers, %w(root@182.92.3.13)
set :production_servers, %w(root@112.126.90.248)

set :ssh_options, {
	forward_agent: true
}