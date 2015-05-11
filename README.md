# Framework on Framework [![Build Status](https://travis-ci.org/akeeba/fof.png)](https://travis-ci.org/akeeba/fof)

## What is FOF? 

FOF (Framework on Framework) is a rapid application development framework for Joomla!.
Unlike other frameworks, it is not standalone. It uses parts of the the Joomla! core
API while providing its own rewritten MVC classes, keeping a strong resemblance to
the existing Joomla! MVC API. This means that you don't have to relearn writing Joomla!
extensions. Instead, you can start being productive from the first day you start using
it. Our goal is to always support the officially supported LTS versions of Joomla! and
not break backwards compatibility without a clear deprecation and migration path.

## Free Software means collaboration

The reason of existence of FOSS (Free and Open Source Software) is collaboration between developers. FOF is no exception; it exists because of and for the community of Joomla! developers. It is provided free of charge and with all of the freedoms of the GPL for you to benefit. And in true Free Software spirit, the community aspect is very strong. Participating is easy and fun.

If you want to discuss FOF, there is [a Google Groups mailing list](https://groups.google.com/forum/?hl=en&fromgroups#!forum/frameworkonframework). This is a peer discussion group where developers working with FOF can freely discuss.

If you have a feature proposal or have found a bug, but you're not sure how to code it yourself, please report it on the list.

If you have a patch, feel free to fork this project on GitHub (you only need a free account to do that) and send a pull request. Please remember to describe what you intended to achieve to help me review your code faster.

If you've found a cool hack (in the benign sense of the word, not the malicious one...), something missing from the documentation or have a tip which can help other developers, feel free to edit the Wiki. We're all grown-ups and professionals, I believe there is no need of policing the wiki edits. If you're unsure about whether a wiki edit is appropriate, please ask on the list.

## How to contribute

Thank you for your interest in improving the Joomla! Rapid Application Development Layer (FOF). No matter how small or how big your contribution is, it has the potential to make a great positive impact to hundreds of people. In this short how-to we are going to answer your questions on making this positive change.

### Which code should I be using?

FOF has its code in a separate Git repository hosted on GitHub. You can access it at [https://github.com/akeeba/fof](https://github.com/akeeba/fof).

No matter if you want to work on code or documentation we kindly ask you to always use the latest [development branch](https://github.com/akeeba/fof/tree/development) on our Git repository. Before starting to fix / improve something make sure it's not already taken care of in this branch. The core value of FOF is having developers use their time productively.

### How can I contribute?

Contrary to popular belief, contributing code is *not* the only way to contribute to FOF. Contributions come in many shapes and forms:

* **Help other people**. FOF is up and foremost a project by people for people. As its use increases, more people will have questions about how to do something. Even if you feel relatively inexperienced with it do remember that someone knows *even less* than you. Don't underestimate yourself!
* **Documentation**. There can never be enough documentation! If you have found a typo, omission, error or if you want to add your tips and tricks it's great.
* **Docblocks**. The code should be self-documenting, so we have docblocks in the top of each file, class and method. Well, that's the theory. Despite our best intentions, sometimes something is missing or is not up to date. Even though it sounds like a trivial contribution it's anything but!
* **Code style**. Code is like poetry. Words matter, but formatting them makes the change between a slump of text and a life-changing poem. When our code follows the Joomla! coding standards we're helping other developers read it and improve upon it. If you have spotted some code not following those standards please fix it and we'll be owing you big time.
* **Unit testing**. The Holy Grail of development is making sure that no matter how many changes we do to it, it's still working without breaking anything. Testing the code ensures that. Manual testing is only possible up to a certain point. Automated testing, in the form of Unit Testing, makes sure that this code quality checks run every time we make a commit. Contributing a Unit Test is definitely one of the most major contributions you can make.
* **Code**. Code, sweet code. Which developer doesn't like the smell of fresh code in the morning? Fixed a bug? Created a feature? Improved a sample component? Made some other awesome modification to the code? Send a PR, explain what you did (and why) and you're instantly helping scores of people.

In the next sections you can see how to perform each of these contributions.

#### Helping other people

As you probably know we have [a mailing list](https://groups.google.com/forum/#!forum/frameworkonframework) where users of FOF help each other. Quite naturally we have newbies who have some basic questions on the fundamentals of using FOF, to experienced users asking arcane questions. The good news is that for every user with a question there is at least another user with an answer. Most certainly you are the one with answers to someone else's questions.

All we ask you to do is to subscribe to this low volume mailing list and volunteer to answer the questions you know the answer for. If you get it wrong, no worries! Someone else with more experience will come in and help both you and the original poster. This way we all learn something new every day – and this applies even to the people who write FOF.

#### Documentation

The main documentation of FOF is written in the DocBook 5 XML format and is located in the [documentation/fof-guide.xml](https://github.com/akeeba/fof/blob/development/documentation/fof-guide.xml) file in the Git repository.

We are not the only project using DocBook XML for our documentation. The Firebird project has a [great documentation page on how to edit DocBook XML files](http://www.firebirdsql.org/manual/docwritehowto-docbook-authoring-tools.html). It basically boils down to you having to use either a plain text editor or a specialised XML editor. While the FOF documentation authors use XMLMind XML Editor and highly recommend it we have to warn you that it's pricey. That said, you can easily edit the file with a plain text editor such as Notepad++ (Windows), gEdit (Linux), Kate (Linux), Smultron (Mac OS X) or TextWrangler (Mac OS X).

Make sure you read the "How do I submit a Pull Request" section to find out how to contribute your changes to the project.

#### Code, docblocks and code style

First make sure that you have checked out the latest development branch. Changes happen on a daily basis, often many times a day. Checking out the latest development branch will ensure you're not trying to improve something already taken care of.

If you are going to touch docblocks and code style only please, please be careful not to make any accidental code changes. If you're dealing with docblocks it's easy for people with commit access to spot any issues; if they see a change in code lines they will know that they have to skip that when committing. Code style changes are actually much tougher as the committer has to go through the original and modified file line-by-line to make sure nothing got inadvertently changes. In order to help them please do small changes at any one time, ideally up to 100 lines of code or less. If you want to make many changes in many files break your work into smaller chunks. If unsure on what to do, ask on the mailing list.

If you are working on a code change it's always a good idea to first discuss this on the list with Nicholas. He's the lead architect of FOF and he's the most qualified to tell you if your intended change is something that can be included and in which version. Usually changes are included right away, unless there are backwards compatibility issues.

Once you have made your changes please sure you read the "How do I submit a Pull Request" section to find out how to contribute your changes to the project.

#### Unit Testing

Unit Testing is an especially sensitive coding area. We'd recommend to first take a look at the [Unit Testing introductory presentation](http://prezi.com/qqv6dqkoqvl3/php-unit-testing-a-practical-approach/) by FOF contributor Davide Tampellini. It will get you up to speed with how testing works.

All tests are stored in the [tests/unit/suites/fof](https://github.com/akeeba/fof/tree/development/tests/unit/suites/fof) directory of the Git repository. As you saw in the presentation the folder structure mirrors that of the fof directory of the Git repository.

Once you have made your changes please sure you read the "How do I submit a Pull Request" section to find out how to contribute your changes to the project.

### How do I submit a Pull Request (PR)?

First things first, you need a GitHub user account. If you don't have one already... what are you waiting for? Just go to github.com, create your free account and log in.

You will need to fork our Git repository. You can do this very easily by going to https://github.com/akeeba/fof and click the Fork button towards the upper right hand corner of the page. This will fork the FOF repository under your GitHub account.

Make sure you clone the repository (the one under *your* account) in your computer. If you're not a heavy Git user don't worry, you can use the GitHub application on your Mac or Windows computer. If you're a Linux user you can just use the command line or your favourite Git client application.

Before making any changes you will need to create a new branch. In the GitHub for Mac application you need to first go into your repository and click the branch name at the bottom right corner of the window. Initially you need to click on "development" to ensure that you are seeing the development, not the master, branch. Then click on it again and type in the name of the new branch, then press Enter. You can now make all of your changes in this branch.

After you're done with your changes you need to publish your branch back to GitHub. Easy peasy! If you're using the GitHub application you need just two steps. First commit all your changed files, which adds them to your local branch. Then click on the Sync Branch button. When it stops spinning everything is uploaded to GitHub and you're ready to finally do your Pull Request!

Now go to github.com, into the forked FOF repository under your user account. Click on the branch dropdown and select your branch. On its left you'll see a green icon with the tooltip "Compare & Review". Click it. Just fill in the title and description –hopefully giving as much information as possible about what you did and why– and your PR is now created! If you need to explain something in greater detail just send a list message.

## Build instructions

### Prerequisites

In order to build the installation package of this library you need to have
the following tools:
* A command line environment. bash under Linux / Mac OS X works best. On Windows you will need to run most tools using an elevated privileges (administrator) command prompt.
* The PHP CLI binary in your path
* Command line Subversion and Git binaries(*)
* PEAR and Phing installed, with the Net_FTP and VersionControl_SVN PEAR packages installed
* libxml and libxslt tools if you intend to build the documentation PDF files

You will also need the following path structure on your system:
* `fof` This repository, a.k.a. MAIN directory
* `buildfiles` [Akeeba Build Tools](https://github.com/akeeba/buildfiles)

### Initialising the repository

All of the following commands are to be run from the MAIN directory. Lines
starting with $ indicate a Mac OS X / Linux / other *NIX system commands. Lines
starting with > indicate Windows commands. The starting character ($ or >) MUST
NOT be typed!

1. You will first need to do the initial link with Akeeba Build Tools, running
   the following command (Mac OS X, Linux, other *NIX systems):

		$ php ../buildfiles/tools/link.php `pwd`

   or, on Windows:

		> php ../buildfiles/tools/link.php %CD%

1. After the initial linking takes place, go inside the build directory:

		$ cd build

   and run the link phing task:

		$ phing link

### Useful Phing tasks

All of the following commands are to be run from the MAIN/build directory.
Lines starting with $ indicate a Mac OS X / Linux / other *NIX system commands.
Lines starting with > indicate Windows commands. The starting character ($ or >)
MUST NOT be typed!

You are advised to NOT distribute the library installation packages you have built yourselves with your components. It
is best to only use the official library packages released by Akeeba Ltd.

1. Relinking internal files

   This is only required when the buildfiles change.

		$ phing link
		> phing link

1. Creating a dev release installation package

   This creates the installable ZIP packages of the component inside the
   MAIN/release directory.

		$ phing git
		> phing git

1. Build the documentation in PDF format

   This creates the documentation in PDF format

		$ phing doc-pdf
		> phing doc-pdf

1. Build the documentation in ePub format

   This creates the documentation in ePub format for use with e-readers (also Kindle, iPad, Android tablets, ...)

		$ phing doc-epub
		> phing doc-epub

   Unlike the other formats, this doesn't generate a single file. Instead, it creates a META-INF and a OEBPS folder in
   the `release` directory.

1. Build the documentation in HTML format

   This creates the documentation as a single-page PDF file

		$ phing doc-html
		> phing doc-html

Please note that all generated files (ZIP library packages, PDF files, HTML files) are written to the
`release` directory inside the repository's root.

## Third Party Links

### Caveat

This section lists FOF (ie, F0F) related links by third parties. 

These links are listed as a courtesy only. Akeeba Limited is not responsible for the content on these sites, nor guarantees the accuracy of any information contain therein. 

### Contribute your links

Submit a PR (see instructions above) and edit this README.md (scroll down to this very section). 

