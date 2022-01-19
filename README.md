Fish shell:
```fish
set project_name [ProjectName] \
&& git clone --depth=1 git@github.com:Arkounay/sf6-boilerplate.git $project_name \
&& cd $project_name \
&& sed -i "s/sf6-boilerplate/$project_name/" .env \
&& sed -i "s/%project_name%/$project_name/g" src/Service/MailingService.php \
&& sed -i "s/%project_name%/$project_name/g" config/packages/qag.yaml \
&& composer install \
&& rm -rf .git \
&& rm README.md \
&& nvm use lts && node -v > .nvmrc \
&& yarn && yarn dev && yarn admin-dev
```