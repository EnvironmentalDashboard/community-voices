# Application Routes
Rough draft of expected application routes. This has been drafted to conceptualize interfaces that will be needed. This last is **incomplete**. Routes that may not need interfaces are indicated with an asterisk (\*).

## Landing

    /landing                                        Community Voices landing page

## User Management

    /users/register
    /users/login
    /users/logout
    /users/:id                                      Lookup user w/ id
    /users/:id/edit                                 Edit user w/ id

## Application Main
The following 

    /slides[?sort=]                                 Lists all slides
    /slides/tagged/:tag[?sort=]                     Lists all slides w/ tag
    /slides/content-category/:category[?sort=]      Lists all slides w/ content category
    /slides/:id                                     Lookup slide w/ id
    /slides/:id/edit                                Edit slide w/ id
    /slides/:id/delete                              Delete slide w/ id
    /slides/new                                     Create a new slide

    /images[?sort=]                                 Lists all images
    /images/tagged/:tag[?sort=]                     Lists all images w/ tag
    /images/content-category/:category[?sort=]      Lists all images w/ content category
    /images/:id                                     Lookup image w/ id
    /images/:id/edit                                Edit image w/ id
    /images/:id/Delete                              Delete image w/ id
    /images/new                                     Create a new image

    /quotes[?sort=]                                 Lists all quotes
    /quotes/tagged/:tag[?sort=]                     Lists all quotes w/ tag
    /quotes/content-category/:category[?sort=]      Lists all quotes w/ content category
    /quotes/:id                                     Lookup quote w/ id
    /quotes/:id/edit                                Edit quote w/ id
    /quotes/:id/Delete                              Delete quote w/ id
    /quotes/new                                     Create a new quote

    /search/?q=[&sort=]                             Searches media

## Application Management

    /content-categories                             Lists all content categories
    /content-categories/:id                         \*Lookup content category w/ id
    /content-categories/:id/edit                    Edit content category w/ id
    /content-categories/:id/delete                  Delete content category w/ id
    /content-categories/new                         Create a new content category

    /org-categories                                 Lists all org categories
    /org-categories/:id                             \*Lookup org category w/ id
    /org-categories/:id/edit                        Edit org category w/ id
    /org-categories/:id/delete                      Delete org category w/ id
    /org-categories/new                             Create a new org category

    /tags                                           Lists all tags
    /tags/:id                                       \*Lookup tags w/ id
    /tags/:id/edit                                  Edit tags w/ id
    /tags/:id/delete                                Delete tags w/ id
    /tags/new                                       Create a new tag

    /locations                                      Lists all locations
    /locations/:id                                  \*Lookup locations w/ id
    /locations/:id/edit                             Edit locations w/ id
    /locations/:id/delete                           Delete locations w/ id
    /locations/new                                  Create a new location
