# todevise

#set :application, "todevise20" # OLD
set :application, "todevise"
set :repository,  "git@github.com:jordioliu/todevise20.git"
set :stages, ["develop", "prod"]
set :default_stage, "develop"
set :deploy_via, :export
set :copy_exclude, [".git/*", ".svn/*", ".DS_Store"]
set :scm, :git
set :use_sudo, false
set :keep_releases, 5

namespace :deploy do
  task :update do
    transaction do
      update_code
      cleanup
    end
  end
  task :finalize_update do
    transaction do
      run "chmod -R g+w #{releases_path}/#{release_name}"
    end
  end
end
