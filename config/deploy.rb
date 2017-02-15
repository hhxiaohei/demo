# config valid only for current version of Capistrano
#lock '3.4.0'

set :application, 'demo'
set :repo_url, 'git@git.nxdai.com:zhanghongyi/demo.git'

set :branch, 'master'
# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/nxstoreback
# set :deploy_to, '/var/www/nxstoreback'
#set :git_strategy, Capistrano::Git::SubmoduleStrategy

# Default value for :scm is :git
set :scm, :git

# Default value for :format is :pretty
# set :format, :pretty

# Default value for :log_level is :debug
# set :log_level, :debug

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
# set :linked_files, fetch(:linked_files, []).push('config/database.yml', 'config/secrets.yml')
set :linked_files, fetch(:linked_files, []).push('.env')

# Default value for linked_dirs is []
# set :linked_dirs, fetch(:linked_dirs, []).push('log', 'tmp/pids', 'tmp/cache', 'tmp/sockets', 'vendor/bundle', 'public/system')
set :linked_dirs, fetch(:linked_dirs, []).push('storage')


# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
# set :keep_releases, 5


namespace :linked_files do

  desc 'Touches all your linked files'
  task :touch do
    on release_roles :all do
      within shared_path do
        fetch(:linked_files, []).each do |file|
          info "Making sure dir exists: #{File.dirname(file)}"
          execute :mkdir, '-p', File.dirname(file)
          execute :touch, file
          info "Touched: #{file}"
        end
      end
    end
  end

  before 'deploy:check:make_linked_dirs', 'linked_files:touch'
  before 'deploy:check:linked_files', 'linked_files:touch'

end

namespace :composer do

    desc "Running Composer Self-Update"
    task :update do
        on roles(:app) do
            execute :composer, "self-update"
        end
    end

    desc "Running Composer Install"
    task :install do
        on release_roles :all do
            within release_path  do
                execute :composer, "install --quiet"
            end
        end
    end

end

namespace :laravel do

    desc "Setup Laravel folder permissions and restart"
    task :permissions do
        on release_roles :all do
            within release_path do
	           execute :chmod, "-R 777 bootstrap"
               execute :service, "php5-fpm restart"
            end
        end
    end

    task :init do
        on release_roles :all do
            within shared_path do
               execute :mkdir, "-p", "storage/framework/cache"
               execute :mkdir, "-p", "storage/framework/sessions"
               execute :mkdir, "-p", "storage/framework/views"
               execute :mkdir, "-p", "storage/logs"
               execute :chmod, "-R 777 storage"
            end
        end
    end

end

namespace :supervisor do
    task :restart do
        on release_roles :all do
            execute :supervisorctl, "restart all"
        end
    end
end

namespace :artisan do

    desc "artisan"
    task :migrate do
        on roles(:app) do
            within release_path do
                execute :composer, "dump-autoload"
                execute :php, "#{release_path}/artisan migrate"
            end
        end
    end

end

namespace :deploy do

  after :published, "composer:install"
  after :published, "laravel:permissions"
  after :published, "artisan:migrate"

  after :restart, :clear_cache do
    on roles(:web), in: :groups, limit: 3, wait: 10 do
      # Here we can do anything such as:
      # within release_path do
      #   execute :rake, 'cache:clear'
      # end
    end
  end

end
