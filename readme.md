# README

@composer: https://stackedit.io/editor
@author: Phuc (ZephyrTekla, ng-pristine, meteor-ng2-pristine, zenithtekla, zetekla, if you like)


## maintain integration and reduction of detach

#### It is important to maintain integration and reduction of detached clones/forks of the main source.

*OR* dev, 1219, 130, live, live_dev, test, test_dev clones/forks of mantis will end up like these:

the dev contains pkgs like this
```json
"dev": {
	"from_phuc": "monitor",
	"from_phuc": "monitor_group",
	"package_a": "a_name",
	"package_b": "b_name"
} // there could be many more installed pkgs/plugins

"live_dev": {
	"from_phuc": "monitor",
	"package_c": "c_name",
	"package_b": "b_name"
} // there could be many more installed pkgs/plugins

"mantis130": {
	"from_phuc": "monitor_group",
	"package_a": "a_name",
	"package_c": "c_name",
	"package_d": "d_name",
	"package_e": "e_name"
} // there could be many more installed pkgs/plugins
```


## implementation of rsa for ease of use

Ground for this implementation
Wanted to do all rsa to Git for 130, dev/mantislive, the production mantislive (root/mantislive) months ago but was unfamiliar with Windows Server authentication and struggling with being unable to execute commands through Git Shell. Now command lines all work.

I have assign `chmod a+x for rsa` file within the dev/mantislive repo, so now after you write some new code and feel certain that a functionality is achieved, you can open GitBash and do the following:

`cd C:/etc && . ./rsa`

`cd C:/inetpub/development/mantislive && . ./rsa`

You can clone rsa file to wherever you cli start (usually pointer starts at `User/`,
known as `~` (I know you know Linux well and Windows is able to perform Bash as Linux does with Terminal shell).
It's possible to do little Bash scripting (Bash programming) to perform ./push -f -m "rX title" -m "description" to save you from typing the following: git add --all, git commit, and git push

after firing ./rsa, you will be able to do the follow:
`git status` // check current status of the repo

`git add --all`

`git commit -m "rX: short title" -m "further description (optional)"` // must be double quotes, for titling it's good to have a look at https://github.com/meanjs/mean/commits/master, they go by fix(config), fix(gulp), fix(build), fix(serviceNamedXYZ), fix(middlewareNamedYZT).

`git push -f origin master`

LET ME KNOW IF THINGS GO WELL OR ANY ISSUE, I'LL BE GLAD TO HELP


## View code at different states (REVERT)

To view the code at different states (like older revisions), it's best to clone or download the current repo (NOT to perform 'revert' right in the project folder)

`git clone https://github.com/zetekla/mantis_live_dev.git myClonedRef`
OR if you like
`git clone https://github.com/zetekla/mantis_live_dev.git C:/inetpub/wwwroot/development/myClonedRef`

located within wwwroot, pointing to the same database mantis_live_dev, you should be able to have the `HEAD` (latest).
Now to revert myClonedRef to older revisions, use *the commit ssa*:

`git checkout <ssa>`



## Things to avoid!!! (Git sometimes can be a little headache)
You can go back and forth among commits and see changes. **BUT** Please refrain from cherry-pick, and reset git commands UNLESS you're really sure what you are doing because it could alter history of the existing repo at https://github.com/zetekla/mantis_live_dev

With **zenithtekla** contains a lot of repos for various grounds of development, the **ZeTekLA** contains the repos of project folders: mantislive, mantis_live_dev, mantis130.
I don't feel necessary to document the mantis1219 through Github because it looks like a fresh installation for reference.