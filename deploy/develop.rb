task :develop do
        # set :branch, 'develop' # OLD
        # set :user, "todeviseapp" # OLD
        # server "dev.todevise.com:1021", :app, :web, :primary => true # OLD
        # set :deploy_to, "/var/www/todevise/web" # OLD
        # after "deploy", "linkdev","composerdev", "npmdev", "assetsdev", "symlink","restartphp" # OLD
        set :branch, 'develop'
        set :user, "coditramuntana"
        server "tessaractic-todevise-dev-web01.ilimit.es", :app, :web, :primary => true
        set :deploy_to, "/home/coditramuntana/todevise/web"
        after "deploy", "linkdev", "composerdev", "npmdev", "symlinkdev", "assetsdev", "restartphpdev"
end
task :composerdev do
    transaction do
      run "ln -nfs #{shared_path}/system/vendor #{releases_path}/#{release_name}/vendor"

      # run "cd #{releases_path}/#{release_name} ; composer -q global require \"fxp/composer-asset-plugin:~1.1.1\"" # OLD
      # run "cd #{releases_path}/#{release_name} ; composer -q install" # OLD
      # run "cd #{releases_path}/#{release_name} ; ./yii mongodb-migrate --interactive=0" # OLD

      run "ln -nfs #{current_release} #{deploy_to}/#{current_dir}"
      run "cd /home/coditramuntana/todevise/web ; docker-compose -q restart"

      run "docker exec -i web_nginx_1 composer -q global require fxp/composer-asset-plugin:~1.1.1"
      run "docker exec -i web_nginx_1 bash -c \"cd /var/www/html && composer -q install\""
      run "docker exec -i web_nginx_1 bash -c \"cd /var/www/html && ./yii mongodb-migrate --interactive=0\""
    end
end
task :npmdev do
    transaction do
      #run "ln -nfs #{shared_path}/system/node_modules #{releases_path}/#{release_name}/node_modules" # OLD
      #run "cd #{releases_path}/#{release_name} ; npm install" # OLD
      # run "cd #{releases_path}/#{release_name} ; docker exec -i todevise20_nginx_1 bash -c \"cd /var/www/html && npm install\"" #OLD
    end
end
task :assetsdev do
    transaction do
      # run "cd #{releases_path}/#{release_name}/tools/gulp ; npm install"
      # run "cd #{releases_path}/#{release_name} ; ./yii asset tools/gulp/assets.php config/assets_compressed.php"
    end
end
task :linkdev do
    transaction do
      run "ln -nfs #{shared_path}/public/images/uploads #{releases_path}/#{release_name}/web/uploads"
      run "ln -nfs #{shared_path}/.env #{releases_path}/#{release_name}/.env"
    end
end
task :symlinkdev do
  transaction do
    # run "ln -nfs #{current_release} #{deploy_to}/#{current_dir}" # OLD
  end
end
task :restartphpdev do
    transaction do
        #run "sudo /usr/sbin/service php5-fpm restart" # OLD
        #run "chmod 775 #{deploy_to}/#{current_dir}/web/assets" # OLD
    end
end
