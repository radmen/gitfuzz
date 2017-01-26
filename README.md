# gitfuzz
Simple CLI app to make fuzz in git repo

# Usage

To work gitfuzz requires generating [config file](#Configuration).

Ater that run this command in GIT repo root directory:

```bash
gitfuzz
```

# Configuration

gitfuzz uses `.gitfuzz` config file which is located in working directory.

To create it run:

```bash
gitfuzz init
```

Example of `.gitfuzz` file:

```json
{
    "authors": [
        "Hilbert Greenholt <haltenwerth@littel.com>",
        "Charlie Wisozk <ansel.block@davis.com>",
        "Earline Willms <mario55@yahoo.com>",
        "Ruben Schmeler <osenger@kovacek.com>",
        "Laila Ullrich <theodore.toy@gmail.com>",
        "Milan Harvey <abraham58@nienow.com>"
    ],
    "commits": {
        "min": 1,
        "max": 10
    }
}
```

* `authors` array of fake commit authors
* `commits.min` lower value of randomly generated number of commits
* `commits.max` upper value of randomly generated number of commits
