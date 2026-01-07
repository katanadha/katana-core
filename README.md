vendor/bin/monorepo-builder release v1.0.0
git tag -d $(git tag -l)

git remote set-url origin git@github.com-coremobile:katanadha/katana-core.git

ssh -T git@github.com-coremobile
ssh-add ~/.ssh/coremobile_id_rsa

ssh -G git@github.com-coremobile | grep -E "hostname|identityfile|identitiesonly"



vendor/bin/monorepo-builder release v1.0.0

git push origin --tags

vendor/bin/monorepo-builder release v1.0.5 --dry-run


git add composer.json packages/*/composer.json
git commit -m "Prepare for next development cycle after 1.0.6"

git push origin main
git push 


git remote set-url origin git@github.com:Kaizen-Nexus/dojo.git
git remote set-url origin git@github.com:Kaizen-Nexus/survey.git


git push origin --delete $(git ls-remote --tags origin | awk '{print $2}' | grep -v '\\^{}$' | cut -f 2)

git tag -l | xargs git tag -d
