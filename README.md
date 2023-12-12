# My boilerplate for new symfony 7 projects

Fish shell, replace `[ProjectName]`:
```fish
set project_name [ProjectName] \
&& set app_secret (tr -dc a-z0-9 </dev/urandom | head -c 32) \
&& git clone --depth=1 git@github.com:Arkounay/sf-boilerplate.git $project_name \
&& cd $project_name \
&& sed -i "s/sf-boilerplate/$project_name/" .env \
&& sed -i "s/__app_secret__/$app_secret/" .env \
&& sed -i "s/__project_name__/$project_name/g" src/Service/MailingService.php \
&& sed -i "s/__project_name__/$project_name/g" config/packages/qag.yaml \
&& sed -i "s/__project_name__/$project_name/g" templates/base.html.twig \
&& sed -i "s/__project_name__/$project_name/g" .env.local \
&& composer install \
&& rm -rf .git \
&& rm README.md \
&& nvm use lts && node -v > .nvmrc \
&& npm install && npm run dev && npm run admin-dev \
&& php bin/console d:d:c \
&& php bin/console d:s:u --force \
&& php bin/console d:f:l -n
```