task :mobile do
        set :branch, 'mobile'
        set :user, "todeviseapp"
        server "dev.todevise.com:1021", :app, :web, :primary => true
        set :deploy_to, "/var/www/mobile.todevise/web"
        after "deploy", "linkmobile","composermobile", "npmmobile", "assetsmobile", "symlink","restartphp"
end
task :composermobile do
    transaction do
      run "ln -nfs #{shared_path}/system/vendor #{releases_path}/#{release_name}/vendor"
      run "cd #{releases_path}/#{release_name} ; composer -q global require \"fxp/composer-asset-plugin:~1.1.1\""
      run "cd #{releases_path}/#{release_name} ; composer -q install"
      run "cd #{releases_path}/#{release_name} ; ./yii mongodb-migrate --interactive=0"
    end
end
task :npmmobile do
    transaction do
      run "ln -nfs #{shared_path}/system/node_modules #{releases_path}/#{release_name}/node_modules"
      run "cd #{releases_path}/#{release_name} ; npm install"
    end
end
task :assetsmobile do
    transaction do
      # run "cd #{releases_path}/#{release_name}/tools/gulp ; npm install"
      # run "cd #{releases_path}/#{release_name} ; ./yii asset tools/gulp/assets.php config/assets_compressed.php"
    end
end
task :linkmobile do
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
