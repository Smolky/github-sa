# github-sa (WIP)
*The aim of this project is to apply PLN patterns to Github in order to guess statictical information about Pull-Requests*

## Introduction
**Sentiment analysis** (SA) is the part of **Natural Language Processing** (NLP) related to identify, extract, quantify, and study affective states and subjective information. SA is widely applied to voice of the customer materials such as reviews and survey responses, online and social media, and healthcare materials for applications that range from marketing to customer service to clinical medicine.

Control repository hosting service such as Github are very popular nowadays. They offers distributed version control and source code management (SCM) functionality of Git as well as adding its own features. It provides access control and several collaboration features such as bug tracking, feature requests, task management, and wikis for every project. [GitHub reports having almost 20 million users and 57 million repositories][1] making it the largest host of source code in the world.

In this project we study the application of SA to reviews of developers in collaborative public repositories in order to identify whether a Pull Request has chances to be successful or not. 

To determine when a pull request has been succesfull is not an easy tasks. Usually PRs are opened for developers to show their code to be reviewed by other developers before to submit their changes. A PRs is a timeline about commits (code) and comments about users. They could be also linked to *issues*, a way for users of the repository to communicate bugs, improves, etc. 


## Github concepts
### Repositories
Github repositories 

### Pull Requests (PRs)
Pull requests are a simple and effective way to do code review and collaboration. Pull requests give you:
- Complete diff (difference) of the changes to each file. 
- Automatic updates so you can see changes as they are made.
- Inline comments so you can pinpoint change suggestions.
- Tasks to help you keep track of what changes need attention.
- Notifications for comments, commits, or approvals.
- Two merge strategy options for Git repositories.

#### One Pull Request. One Concern [ref][2]
A commit represents a single atomic change, an indivisible change. It can succeed entirely or it can fail entirely, but it cannot partly succeed. 
In a Git commit, we measure "succeed" as the ability to deliver value to the application. "Value" is not just about business value, it can represent payment of technical debt, legibility fixes or internal interface changes, but it cannot contain certain refactoring or whitespace changes that don't have a clear purpose and therefore can succeed even if part of the change is omitted.

A *Pull Request* is more than just a set of commits. While a commit can only contain a single change, a Pull Request can contain one or more changes that together form a high-level concern. In the normal cycle of a PRs they are merged to the main brach.

While the code is been reviewed, another code can be added to other developers. The concepts of *rebase* and *commit Squashing* are useful to simplify the repository history. Commit squashing refers to merge all commits in a pull request inside one. Rebase is a operation needed to update your branch with possible new code in the master branch. Doing a rebase, developers may evit merging conflicts.


### Workflow models
Github has two major models to work: *Fork and pull model* and *Shared repository model*

- **Fork and pull model (aka Forking Workflow)**
In this model, all the changes are made by developers in tehir local machines and the administrator of the main repository decides what and when the changes will be added to the repository. This model is popular in big projects and eases to administrators becasue they do not need to manage permissions.

- **Centralized Workflow**
This workflow does not need PRs to maintain the code. Code is directly pushed into the master branch

    - **Git Feature Branch Workflow**: This workflow consist in a master branch. Every develop creates new branches to create new features. When a branch wants to be merged, PRs can be created so other collaborators could review and discuss the code.
    - **Gitflow Workflow**: This workflow is useful to big projects or projects with a scheduled release cycle. It contains branches for master, development, features, versions and hotfixes. In the development branches the developers creates and merge features and dedicated branches of versions are used to review, perform bug-fixes and generate documentation. Hotfixes branches are used to create security patches directly to the master branch.
    - **Shared repository model**: In this model, a permission system is created. Pull-Requests are created to review the work. This model is popular in private projects

### GitHub API


## Requirements
The next project has the next requirements

- PHP >= 5.5.0



## How to install
To deploy this project, please, follow the next steps

1. Clone/fork this repository
2. Copy ```config.sample.php``` to ```config.php```
3. Modify ```config.php``` with your server settings and provide an *github access token*


## How to run
Once the application has been properly configured, you can achieve results this way
```
    http://my-local-server.com/github-sa/[:owner]/[:name]
```

where ```owner``` and ```vendor``` are the values to identify the repository. For example:
```
    http://my-local-server.com/github-sa/magento/magento2
```

will fetch the PRs from Magento2 repository.



## References
[1]: https://octoverse.github.com/
[2]: https://medium.com/@fagnerbrack/one-pull-request-one-concern-e84a27dfe9f1

  
- General https://help.github.com/categories/collaborating-with-issues-and-pull-requests/
- Comparing Workflows
    - https://www.atlassian.com/git/tutorials/comparing-workflows
    - https://www.atlassian.com/git/tutorials/comparing-workflows/feature-branch-workflow
    - https://www.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow
    - https://www.atlassian.com/git/tutorials/comparing-workflows/forking-workflow

- Workflows https://help.github.com/articles/about-collaborative-development-models/
- About pull request https://help.github.com/articles/about-pull-requests/
- Clossing issues with keywords https://help.github.com/articles/closing-issues-using-keywords/
- Creating a pull request https://help.github.com/articles/creating-a-pull-request/
- Forking https://gist.github.com/Chaser324/ce0505fbed06b947d962
- Merge pull request https://help.github.com/articles/merging-a-pull-request/
