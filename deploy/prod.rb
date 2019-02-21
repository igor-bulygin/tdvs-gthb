task :prod do
        set :branch, 'master'
        set :user, "coditramuntana"
        server "tessaractic-todevise-pro-web01.ilimit.es", :app, :web, :primary => true
        set :deploy_to, "/home/coditramuntana/todevise/web"
        after "deploy", "composerprod"
end
task :composerprod do
    transaction do
      run "ln -nfs #{shared_path}/.env #{releases_path}/#{release_name}/.env"

      run "ln -nfs #{shared_path}/system/vendor #{releases_path}/#{release_name}/vendor"
      run "ln -nfs #{shared_path}/system/runtime #{releases_path}/#{release_name}/runtime"
      run "ln -nfs #{shared_path}/system/web/assets #{releases_path}/#{release_name}/web/assets"
      run "ln -nfs #{shared_path}/public/images/uploads #{releases_path}/#{release_name}/web/uploads"

      run "ln -nfs #{current_release} #{deploy_to}/#{current_dir}"
      run "cd /home/coditramuntana/todevise/web ; docker-compose restart"

      run "docker exec -i web_nginx_1 chown -R 1101:999 /var/www/html/vendor"
      run "docker exec -i web_nginx_1 chown -R 1101:999 /var/www/html/web"
      run "docker exec -i web_nginx_1 chown -R 1101:999 /var/www/html/runtime"
      run "docker exec -i web_nginx_1 chown -R 1101:999 /var/www/html/.env"
    end
end
