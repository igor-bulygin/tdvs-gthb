task :prod do
        set :branch, 'master'
        set :user, "todeviseapp"
        role :web, "ws1.todevise.com", :primary => true
        set :deploy_to, "/var/www/todeviseapp/pro"
        after "deploy", "linkprod", "symlink"
end
task :linkprod do
    transaction do
      #run "ln -nfs #{shared_path}/system/db_ini.php #{releases_path}/#{release_name}/db_ini.php"
      #run "ln -nfs #{shared_path}/log #{releases_path}/#{release_name}/logs"
      #run "ln -nfs #{shared_path}/public/upload #{releases_path}/#{release_name}/upload"
      #run "ln -nfs #{shared_path}/public/_thumbs #{releases_path}/#{release_name}/library/PHPThumb/_thumbs"
    end
end
task :symlink do
	transaction do
		run "ln -nfs #{current_release} #{deploy_to}/#{current_dir}"
	end
end
