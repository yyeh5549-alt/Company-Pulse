# Company Pulse - 專案文件與程式碼導覽 (Project Documentation & Guide)

---

## Part 1: 專案架構與開發進度總結 (Project Summary)

### 1. 執行摘要與架構概覽 (Executive Summary & Architecture Overview)

* **中文：** 本團隊已完成 **Company Pulse** 的基礎架構開發。這是一款專為初創企業內部溝通設計的社交網路 Web 應用程式。
* **English:** Our team has completed the foundational architecture for **Company Pulse**, an internal corporate social networking web application designed for startup communication.

* **中文：** 本專案採用經典的 **Robin's Nest 架構**，前端使用 **HTML5、模組化 CSS3 與 JavaScript/jQuery AJAX**，後端則結合 **PHP Data Objects (PDO)** 與 **MySQL 關聯式資料庫**進行伺服器端渲染與資料處理。
* **English:** The application follows the monolithic **Robin's Nest architecture**, integrating a server-rendered PHP backend using **PHP Data Objects (PDO)** with MySQL, and a modern front-end layout powered by **HTML5, modular CSS3, and JavaScript/jQuery AJAX**.

---

### 2. 模組功能拆解 (Component Breakdown by Module)

#### 全域版面與 CSS 設計系統 (Global Layout & CSS Design System)
* **包含檔案 (Files):** `css/styles.css`, `includes/header.php`, `includes/footer.php`
* **中文 (CSS 變數)：** 建立統一的顏色主題（主色藍、淺灰背景、狀態提示色）、字型與間距規範，確保全站視覺一致性。
* **English (CSS Variables):** Standardized color palettes (primary blues, light gray backgrounds, status colors), typography families, and spacing tokens to ensure visual consistency.
* **中文 (盒模型與通用類別)：** 設定 `box-sizing: border-box` 全域重置，並撰寫可複用的 UI 組件類別（如 `.card`、`.btn`、`.form-input`、`.avatar-thumbnail`），避免直接針對 ID 進行樣式綁定。
* **English (Box Model & Utility Classes):** Enforced a clean `box-sizing: border-box` reset and created reusable component classes (`.card`, `.btn`, `.form-input`, `.avatar-thumbnail`) rather than styling IDs directly.
* **中文 (動態導覽列)：** 在 `header.php` 中建構選單樣板，根據使用者登入狀態（訪客顯示：首頁、註冊、登入；會員顯示：首頁、成員目錄、好友、訊息、編輯個人資料、登出）動態切換顯示內容。
* **English (Dynamic Header Navigation):** Created a template layout in `header.php` that dynamically changes menu items depending on whether a user is a guest (Home, Sign Up, Log In) or an authenticated member (Home, Members, Friends, Messages, Edit Profile, Log Out).

#### 資料庫架構與工具層 (Database Architecture & Utility Layer)
* **包含檔案 (Files):** `includes/setup.php`, `includes/functions.php`
* **中文 (PDO 連線)：** 使用 PHP Data Objects 建立安全的資料庫連線，並設定完整的例外處理（Exception Handling）與預備語法（Prepared Statements）機制。
* **English (PDO Database Connection):** Established secure database access using PHP Data Objects with exception handling and prepared statement configurations.
* **中文 (自動化建表)：** `setup.php` 提供第一次執行時的自動化建表功能，建立 4 張主要關聯表 (`members`, `profiles`, `friends`, `messages`)。
* **English (Automated Table Creation):** Implemented an automated setup script (`setup.php`) that initializes four relational MySQL tables (`members`, `profiles`, `friends`, `messages`).
* **中文 (資安防護)：** 撰寫 `sanitizeString()` 與 `destroySession()` 等工具函式，過濾 HTML 標籤以防止跨站腳本攻擊 (XSS)，並確保 Session 安全銷毀。
* **English (Security & Sanitization):** Developed helper functions (`sanitizeString()`, `destroySession()`) to strip HTML tags, prevent cross-site scripting (XSS), and manage session destruction securely.

#### 使用者驗證與非同步檢查 (User Authentication & Asynchronous Validation)
* **包含檔案 (Files):** `signup.php`, `login.php`, `checkuser.php`, `js/main.js`
* **中文 (即時 AJAX 檢查)：** 在 `main.js` 中實作非同步 JavaScript 事件，當使用者在註冊頁面輸入帳號並移開焦點時，背景會自動發送 POST 請求至 `checkuser.php`，無需重新整理頁面即可即時顯示帳號是否可用。
* **English (Real-Time AJAX Username Check):** Built an asynchronous JavaScript event in `main.js` that triggers on input blur during sign-up. It sends a background POST request to `checkuser.php` to inform users if a username is available or taken in real time without refreshing the page.
* **中文 (安全驗證)：** 採用 `password_hash` 與 `password_verify` 進行密碼雜湊化處理，確保明文密碼不會直接存入資料庫。
* **English (Secure Authentication):** Implemented password hashing (`password_hash` and `password_verify`) during registration and login to ensure passwords are never stored in plain text.

#### 個人資料與影像處理 (Profile & Image Processing)
* **包含檔案 (Files):** `profile.php`
* **中文 (簡介與頭像上傳)：** 支援使用者更新簡介文字及上傳個人頭像（格式支援 `.jpg`、`.png`、`.gif`）。
* **English (Bio & Image Upload):** Enabled users to update their bio and upload profile avatars (`.jpg`, `.png`, `.gif`).
* **中文 (伺服器圖片裁切)：** 利用 PHP GD 繪圖函式庫對上傳圖片進行自動裁切與重採樣，縮放為標準的 100x100 縮圖並儲存至 `uploads/avatars/` 目錄。
* **English (Server-Side Image Resizing):** Used PHP’s GD library to process uploaded avatars by automatically cropping and resampling them into standard 100x100 thumbnail images saved in `uploads/avatars/`.

#### 社群互動與好友系統 (Member Connections & Social Features)
* **包含檔案 (Files):** `members.php`, `friends.php`
* **中文 (成員目錄)：** `members.php` 列出所有已註冊會員，並提供追蹤與取消追蹤的按鈕。
* **English (Member Directory):** `members.php` displays all registered users and allows members to follow or drop connections with other users.
* **中文 (關係邏輯分類)：** `friends.php` 透過陣列交集與差集演算法，自動將連線關係分類為雙向好友 (Mutual Friends)、粉絲 (Followers) 及追蹤中 (Following)。
* **English (Relationship Management):** `friends.php` performs array intersection and difference logic on database records to categorize connections into Mutual Friends, Followers, and Following.

#### 訊息互動模組 (Messaging Module)
* **包含檔案 (Files):** `messages.php`
* **中文 (公開牆與私人密語)：** 支援發布公開動態或傳送私人密語 (Whisper)。
* **English (Public Feed vs. Private Whispers):** Provides a message board supporting public posts and private "whispered" messages.
* **中文 (隱私與刪除權限)：** 在資料庫查詢層級加上安全限制，確保私人密語僅有傳送者與接收者有權讀取，並允許收件者刪除訊息。
* **English (Privacy Controls & Deletion):** Database queries enforce privacy safeguards so private whispered notes are strictly visible only to the sender and recipient, while enabling recipients to erase posts from their feed.

---

## Part 2: 程式碼運作原理白話講解 (Educational Explanation)

### 概念比喻 (The Analogy)
* **中文：** 想像我們在蓋一座「數位線上俱樂部」。前端 (Frontend) 是裝潢與設計師，負責牆壁顏色與按鈕；後端 (Backend) 是管家與金庫管理員，負責管理密碼與檔案庫。
* **English:** Imagine building a "digital clubhouse." Front-End is the Decorator & Architect managing paint and buttons; Back-End is the Vault Manager keeping passwords and files safe.

---

### 1. 視覺與裝潢設計 (Design & Styling)
* **`css/styles.css` — 設計藍圖 (The Master Blueprint)**
  * **中文：** 設定全站標準顏色與 Class 選擇器（如 `.btn`）。Class 就像是制服，讓所有穿上制服的按鈕自動變漂亮，不需要一個個單獨設定。
  * **English:** Sets up global color variables and Class Selectors (like `.btn`). Classes act like uniforms—any button wearing the `.btn` uniform instantly gets styled consistently without repeating code.

---

### 2. 網站骨架 (Site Skeleton)
* **`includes/header.php` & `includes/footer.php` — 門面與屋頂 (Frame & Roof)**
  * **中文：** 統一全站頁首與頁尾。`header.php` 還是個聰明的門衛，會根據使用者是否登入，自動切換顯示「註冊/登入」或「好友/訊息/登出」。
  * **English:** Reusable header and footer across all pages. `header.php` acts as a smart door attendant, automatically toggling menu items between guest actions (Sign Up/Log In) and member actions (Friends/Messages/Log Out).

* **`index.php` — 俱樂部大門 (The Front Door)**
  * **中文：** 訪客第一個看到的歡迎頁面，引導大家登入或加入會員。
  * **English:** The main welcome landing page introducing the platform and inviting users to sign up or log in.

---

### 3. 安全警衛與後端設定 (Security & Vault Setup)
* **`includes/functions.php` — 工具箱 (Helper Toolkit)**
  * **中文：** 建立資料庫連線，並提供 `sanitizeString()` 消毒工具，防止駭客輸入惡意程式碼。
  * **English:** Connects to the database and provides `sanitizeString()` to clean text inputs, protecting the site from malicious scripts.

* **`includes/setup.php` — 建立檔案櫃 (Building Filing Cabinets)**
  * **中文：** 自動創建 4 個資料表：`members` (帳密), `profiles` (簡介), `friends` (追蹤關係), `messages` (訊息記錄)。
  * **English:** Automatically creates 4 MySQL database tables: `members` (credentials), `profiles` (bios), `friends` (follows), and `messages` (posts & whispers).

---

### 4. 帳號驗證 (Authentication)
* **`js/main.js` & `checkuser.php` — 飛速檢查員 (AJAX Assistant)**
  * **中文：** 當輸入帳號並移開焦點時，背景默默傳送資料給 `checkuser.php`，無需刷頁即可即時顯示「帳號可用」或「帳號已被使用」。
  * **English:** On input blur, `main.js` sends background requests to `checkuser.php` to immediately show if a username is available without reloading the page.

* **`signup.php` & `login.php` — 通關閘門 (Gatekeepers)**
  * **中文：** `signup.php` 將密碼加密成亂碼存入資料庫；`login.php` 比對密碼並發放 Session VIP 通行證。
  * **English:** `signup.php` encrypts passwords using hashing before storing; `login.php` verifies credentials and grants a Session VIP keycard.

* **`logout.php` — 銷毀通行證 (Session Cleanup)**
  * **中文：** 登出時註銷通行證，保護帳號安全。
  * **English:** Safely destroys active sessions upon logging out to protect user accounts.

---

### 5. 個人化與社群 (Profiles & Social Hub)
* **`profile.php` — 個人置物櫃 (Personal Locker)**
  * **中文：** 允許編輯簡介並上傳大頭貼，後端會自動把大圖片裁剪成 100x100 的標準縮圖。
  * **English:** Allows updating bio text and uploading avatars, automatically resizing large photos into neat 100x100 thumbnails.

* **`members.php` & `friends.php` — 社群圈子 (Social Network & Connections)**
  * **中文：** `members.php` 提供成員目錄與追蹤按鈕；`friends.php` 透過邏輯運算自動把互相追蹤的人升格為「雙向好友」。
  * **English:** `members.php` displays member directory and follow actions; `friends.php` computes relationships to automatically promote mutual follows into "Mutual Friends."

* **`messages.php` — 傳聲筒與公佈欄 (Message Hub)**
  * **中文：** 支援發布所有人可見的「公開訊息」或僅雙方可見的「私人密語」，並允許收件者刪除留言。
  * **English:** Supports posting "Public Messages" visible to all or "Private Whispers" restricted to sender and recipient, with deletion rights for feed owners.
