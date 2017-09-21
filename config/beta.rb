task :beta do
        set :branch, 'beta'
        set :user, "todeviseapp"
        server "beta.todevise.com", :app, :web, :primary => true
        set :deploy_to, "/var/www/todevise/web"
        after "deploy", "linkbeta","composerbeta", "npmbeta", "assetsbeta", "symlink","restartphp"
end
task :composerbeta do
    transaction do
      run "ln -nfs #{shared_path}/system/vendor #{releases_path}/#{release_name}/vendor"
      run "cd #{releases_path}/#{release_name} ; composer -q global require \"fxp/composer-asset-plugin:~1.1.1\""
      run "cd #{releases_path}/#{release_name} ; composer -q install"
      run "cd #{releases_path}/#{release_name} ; ./yii mongodb-migrate --interactive=0"
    end
end
task :npmbeta do
    transaction do
      run "ln -nfs #{shared_path}/system/node_modules #{releases_path}/#{release_name}/node_modules"
      run "cd #{releases_path}/#{release_name} ; npm install"
    end
end
task :assetsbeta do
    transaction do
      # run "cd #{releases_path}/#{release_name}/tools/gulp ; npm install"
      # run "cd #{releases_path}/#{release_name} ; ./yii asset tools/gulp/assets.php config/assets_compressed.php"
    end
end
task :linkbeta do
    transaction do
      run "ln -nfs #{shared_path}/public/images #{releases_path}/#{release_name}/web/uploads"
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



