task :redesign do
        set :branch, 'redesign'
        set :user, "todeviseapp"
        server "dev.todevise.com:1021", :app, :web, :primary => true
        set :deploy_to, "/var/www/redesign/web"
        after "deploy", "linkred","composered", "npmred", "symlink","restartphp"
end
task :composered do
    transaction do
      run "ln -nfs #{shared_path}/system/vendor #{releases_path}/#{release_name}/vendor"
      run "cd #{releases_path}/#{release_name} ; composer -q global require \"fxp/composer-asset-plugin:~1.1.1\""
      run "cd #{releases_path}/#{release_name} ; composer -q install"
      run "cd #{releases_path}/#{release_name} ; ./yii mongodb-migrate --interactive=0"
    end
end
task :npmred do
    transaction do
      run "ln -nfs #{shared_path}/system/node_modules #{releases_path}/#{release_name}/node_modules"
      run "cd #{releases_path}/#{release_name} ; npm install"
    end
end
task :linkred do
    transaction do
      run "ln -nfs /var/www/todevise/web/shared/public/images/uploads #{releases_path}/#{release_name}/web/uploads"
      run "ln -nfs /var/www/todevise/web/shared/public/thumbor_resized #{releases_path}/#{release_name}/web/thumbor_resized"
      run "ln -nfs /var/www/todevise/web/shared/public/thumbor_cache #{releases_path}/#{release_name}/web/thumbor_cache"
    end
end
task :symlink do
  transaction do
    run "ln -nfs #{current_release} #{deploy_to}/#{current_dir}"
  end
end

task :restartphp do
    transaction do
        run "sudo /usr/sbin/service php5-fpm restart"
        run "chmod 775 #{deploy_to}/#{current_dir}/web/assets"
    end
end