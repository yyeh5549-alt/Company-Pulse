# Company Pulse User Guide

Company Pulse is our internal social network. You can create an account, find coworkers, follow them, send public or private messages, and keep a short profile with a picture.

## 1. Starting the site (first time only)

On a Mac, open Terminal in the project folder and run:

```
./setup_env.sh      # one time: installs PHP and MySQL, creates the database
./launch_website.sh # every time: starts the site
```

Then open **http://localhost:8000** in your browser. Other people on the same Wi-Fi can use the "Network" address that `launch_website.sh` prints (for example `http://192.168.1.20:8000`). Press `Ctrl+C` in Terminal to stop the site.

## 2. Getting around

The menu bar at the top is on every page.

- The page you are on is **bold and underlined** in the menu.
- Under the menu, a line shows whether you are logged in and as whom.
- Clicking **Company Pulse** (top left) always takes you Home.
- After most actions, a colored banner at the top confirms what happened (green = done, red = problem, blue = information).

| Menu item | What it does |
|---|---|
| Home | Your own picture and bio, plus shortcut buttons |
| Members | List of everyone; follow or unfollow people |
| Friends | Your connections: who follows you and who you follow |
| Messages | Your own message page |
| Edit Profile | Change your bio and picture |
| Log Out | End your session |

Pages other than Home, Sign Up and Log In need you to be logged in. If you open one while logged out, you are sent to the Log In page.

## 3. How to do common tasks

### Create an account
1. Click **Sign Up**.
2. Type a username: 3-16 characters, using letters, numbers, or underscores. While you type, the page tells you if the name is taken or available.
3. Type a password: 6-16 characters.
4. Click **Sign Up**. You are taken to the Log In page with a confirmation banner.

### Log in and out
1. Click **Log In**, enter your username and password, and click **Log In**.
2. If something is wrong, a red message explains it and your username stays filled in.
3. Click **Log Out** in the menu when you are done.

### Find and follow a coworker
1. Click **Members**.
2. Use the **Search members** box to filter the list by name.
3. Click **Follow** next to a name. A banner confirms it, and the button changes to **Unfollow**.
4. A tag next to a name shows the relationship: **→ Follows you** (they follow you) or **↔ Mutual friend** (you follow each other).

### See your connections
Click **Friends**. People are grouped as:
- **Mutual Friends**: you follow each other.
- **Your Followers**: they follow you, you do not follow them.
- **Members You Follow**: you follow them, they have not followed back.

Use the **Message** button next to a name to open their page.

To see another member's connections, open their page (click their name on Members) and click **View [name]'s connections**.

### Send a message
1. Click a member's name (on Members or Friends). Their picture, bio, and messages appear.
2. Type in **Your message**.
3. Choose **Public** (everyone can read it) or **Private whisper** (only you and that member can read it).
4. Click **Send Message**. A banner confirms it and the message appears in the list.

### Read and erase messages
- Click **Messages** in the menu to see messages sent to you. Private whispers are shown with a yellow background.
- Click **Erase** next to a message sent to you to delete it. You will be asked to confirm, and this cannot be undone.

### Edit your profile
1. Click **Edit Profile**.
2. Write something in **About me**.
3. To change your picture, click **Choose file** and pick a .jpg, .png, or .gif of up to 2 MB. Leave this empty to keep your current picture.
4. Click **Save Profile**. If you have no picture, a blue circle with your first initial is shown instead.

## 4. Troubleshooting

| Problem | What to try |
|---|---|
| "Database connection failed" | MySQL is not running. Run `./launch_website.sh` again. |
| Page will not load | Check Terminal: `launch_website.sh` must still be running. |
| "Username or password is incorrect" | Check spelling and caps lock. Usernames and passwords are case-sensitive. |
| "That username is already taken" | Choose a different username. |
| Picture will not upload | Use a .jpg, .png, or .gif under 2 MB. |
| Teammate cannot open the Network address | Both computers must be on the same Wi-Fi, and macOS may ask you to allow incoming connections. |

## 5. What changed in this usability update

These changes came from reviewing the site with real tasks in mind:

- **Clear feedback**: banners confirm sign up, log in, follow, unfollow, send, erase, and save.
- **No dead ends**: logged-out visitors are redirected to Log In (before, they saw a blank page). Logging out returns you Home as a guest.
- **Better forms**: hints under fields, a username check as you type, labeled fields, your typing is kept after an error, and messages explain what to fix.
- **Members page**: avatars, a Follow/Unfollow button, "Follows you" and "Mutual friend" tags, and a search box.
- **Profiles are visible**: a member's picture and bio show on your Home page and at the top of their message page, and you can view any member's connections.
- **Safer actions**: Erase asks for confirmation; refreshing a page no longer repeats an action.
- **Fixes**: removed a stray "[cite: 1]" on the Home page, fixed the broken default picture, and uploads are checked before saving.
- **Mobile friendly**: the menu wraps on small screens, and keyboard focus is clearly visible.
