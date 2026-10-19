# VisitWeho Plugin Changelog
All notable changes to this project will be documented in this file.

## [2.15.0] - 2020-11-17 Release
- Adds hide events CTA property to offer obj

## [2.14.0] - 2020-9-01 Release
- Adds menu special offer field ID + support for multiple offers

## [2.13.0] - 2020-3-25 Release
- Adds hotel tile message data

## [2.12.0] - 2020-3-5 Release
- Adds wysiwyg for emergency status
- Adds yoast seo variables for events

## [2.11.0] - 2020-3-5 Release
- Adds API endpoints for home slideshow
- Adds home slide content class

## [2.10.1] - 2020-2-20 Hotfix
- silently execute post deploy
- fix version

## [2.10.0] - 2020-2-20 Release
- Adds lazy loading to mega menu_item
- Adds stories API endpoints

## [2.9.3] - 2020-2-19 Hotfix
- API fetch page data by slug

## [2.9.2] - 2020-2-13 Hotfix
-RM special headline/img refs from offer

## [2.9.1] - 2020-2-13 Hotfix
- Rebuild cache removal fixe

## [2.9.0] - 2020-2-13 Release
- Increase timeout for post deploy scripts
- Page object data
- Page endpoints
- Adds related story data point to location
- rm old file
- Adds location save cron
- Location story saved cron tweaks
- Related stories prefetch for hotels cron

## [2.8.1] - 2020-2-11 Hotfix
- Enhancements for post-deploy hooks

## [2.8.0] - 2020-2-11 Release
- Fixes issue with mega-menu regeneration cron
- Adds cache clearing func + cron
- post deploy cache bash scripts
- cron automated cache rebuild

## [2.7.2] - 2020-1-23 Release
- Adds proper status headers for API response

## [2.7.1] - 2020-1-23 Release
- Extends nonce lifespan

## [2.7.0] - 2020-1-22 Release
- Adds gallery-image panel for Hotels
- Adds drag-and-drop reordering
- Updates Hotel class for new gallery-items data

## [2.6.0] - 2020-1-6 Release
- Adds not-in param to events endpoint
- Adds Featured Fill Query flag for featured-event queries

## [2.5.0] - 2020-1-1 Release
- Adds schema description filter to Event object

## [2.4.4] - 2019-12-20 Hotfix
- Adds condition for Gutenberg rich-text patch

## [2.4.3] - 2019-12-19 Hotfix
- Updates cookie consent notification

## [2.4.2] - 2019-12-12 Hotfix
- Removes duplicate menu widget generation function

## [2.4.1] - 2019-12-12 Hotfix
- Removes duplicate menu widget generation function

## [2.4.0] - 2019-12-12 Release
- Mega Menu Release

## [2.3.1] - 2019-12-10 Hotfix
- Fixes double ref to image size
- Adds medium-large img size

## [2.3.0] - 2019-12-10 Release
 - Excludes header selector from Neighborhood page templates
 - Adds neigh. desc to admin page save
 - Adds itinerary collection selector to pagedata
 - Updates admin for single-itinerary selection
 - Changes field name for activity edit subheading
 - Adds subheading to activity obj
 - Adds experience/story edit selector
 - Adds id exclusion filter to get_hotels
 - Attach locations to neighborhood page editor

## [2.2.1] - 2019-11-26 Hotfix
 - Adds pre-heading to itinerary builder

## [2.2.0] - 2019-11-26 Release
 - Adds Itinerary post types
 - Itinerary types meta box boilerplate
 - Adds activity tag
 - Adds activity search infrastructure + endpoints + sassify admin CSS
 - Adds activity and collection models
 - Rm obj output
 - Base for new group-oriented sorting
 - Base components for new itinerary ui
 - Move-grab structure for group rows/activity sort
 - Completes front-end component of itin. builder
 - Data saves for new collections structure + recalls
 - Rm console logs
 - Re-enable collection Gutenberg + add group input focus
 - Adds collection queries and API calls

Changes group label heading

## [2.1.0] - 2019-11-4 Release
 - Disables crowdriff admin fields
 - Remove weather API integration
 - Removes crowdriff cron schedule
 - Removes sharethis data and functionality
 - Removes Instagram Fetch and API endpoint
 - Disables crowdriff API fetch

## [2.0.0] - 2019-11-4 Release
 - Removes featured media query from story object
 - Videos obj property update for media item

## [1.5.0] - 2019-10-21 Release
- Adds API authorization to admin endpoint fetch for offers
- Fetch multiple offers per hotel obj

## [1.4.2] - 2019-6-10 Hotfix
- Increase events expire to 4x daily

## [1.4.1] - 2019-4-30 Hotfix
- Conditional check for meta in event object

## [1.4.0] - 2019-4-29 Release
- Adds campaign slug to offer object and admin saving

## [1.3.3] - 2019-4-18 Hotfix
- Misc bug fixes in venue place id fetch, user profile mod

## [1.3.2] - 2019-4-14 Hotfix
- Increases event fetch curl timeout and conditional wp_error check

## [1.3.1] - 2019-3-14 Hotfix
- Adds amp-video conversion parser support for YouTube shortlink

## [1.3.0] - 2019-3-13 Release
- Adds output handler for reservation widget

## [1.2.1] - 2019-3-13 Hotfix
- Patches conflict between autoptimize and AMP

## [1.2.0] - 2019-3-12 Release
- Ads support for AMP video output

## [1.1.1] - 2019-3-7 Hotfix
- Patches noopener/rel/target issue

## [1.1.0] - 2019-3-7 Release
- Adds crowdriff API fetching and slideshow
- Adds offer expiration 404 handler

## [1.0.0] - 2017-9-5 Release
- Initial release
