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


## Release
# 1. Run release
vendor/bin/monorepo-builder release v1.0.0

# 2. Push release commit
git push origin main

# 3. Push the tag
git push origin --tags

# 4. Prepare for next dev cycle
git add composer.json packages/*/composer.json
git commit -m "Prepare for next development cycle after 1.0.0"
git push origin main


========
# Run release
vendor/bin/monorepo-builder release v1.0.0

# Check for changes
git status

# If files are modified, commit first
git add composer.json packages/*/composer.json CHANGELOG.md
git commit -m "Release v1.0.0"

# Push commit
git push origin main

# Push tag
git push origin --tags

# Prepare next dev cycle
git add composer.json packages/*/composer.json
git commit -m "Prepare for next development cycle after 1.0.0"
git push origin main
