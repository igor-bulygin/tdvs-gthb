task :develop do
        set :branch, 'develop'
        set :user, "todeviseapp"
        server "dev.todevise.com:1021", :app, :web, :primary => true
        set :deploy_to, "/var/www/todevise/web"
        after "deploy", "linkdev","composerdev", "npmdev", "symlink"
end
task :composerdev do
    transaction do
      run "ln -nfs #{shared_path}/system/vendor #{releases_path}/#{release_name}/vendor"
      run "cd #{releases_path}/#{release_name} ; composer -q install"
      #run "cd #{releases_path}/#{release_name} ; bower install"
    end
end
task :npmdev do
    transaction do
      run "ln -nfs #{shared_path}/system/node_modules #{releases_path}/#{release_name}/node_modules"
      run "cd #{releases_path}/#{release_name} ; npm install"
      #run "cd #{releases_path}/#{release_name} ; bower install"
    end
end
task :linkdev do
    transaction do
      #run "ln -nfs #{shared_path}/system/db_ini.php #{releases_path}/#{release_name}/db_ini.php"
      #run "ln -nfs #{shared_path}/log #{releases_path}/#{release_name}/logs"
      #run "ln -nfs #{shared_path}/public/upload #{releases_path}/#{release_name}/upload"
    end
end
task :symlink do
  transaction do
    run "ln -nfs #{current_release} #{deploy_to}/#{current_dir}"
  end
end
