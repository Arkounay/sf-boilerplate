Fish shell:
```fish
set project_name [ProjectName] \
&& git clone --depth=1 git@github.com:Arkounay/sf6-boilerplate.git $project_name \
&& cd $project_name \
&& sed -i "s/sf6-boilerplate/$project_name/" .env \
&& sed -i "s/__project_name__/$project_name/g" src/Service/MailingService.php \
&& sed -i "s/__project_name__/$project_name/g" config/packages/qag.yaml \
&& composer install \
&& rm -rf .git \
&& rm README.md \
&& nvm use lts && node -v > .nvmrc \
&& npm install && npm run dev && npm run admin-dev \
&& php bin/console d:d:c \
&& php bin/console d:s:u --force \
&& php bin/console d:f:l -n
```
