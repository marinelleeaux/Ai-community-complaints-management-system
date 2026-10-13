-- ==============================================================================
-- SUPABASE ROW LEVEL SECURITY (RLS) POLICIES
-- Project: AI-Powered Community Complaint Management System
-- Barangay North Poblacion, Maramag, Bukidnon
-- ==============================================================================
--
-- ARCHITECTURE NOTE:
-- Laravel interacts with PostgreSQL via the direct/pooler database connection.
-- When Laravel runs Eloquent queries, authorization is strictly verified in
-- Laravel Policies, Middleware, and Form Requests.
-- 
-- The policies below secure direct Supabase Client SDK access (browsers/mobile)
-- ensuring cross-user isolation and role-based data protection if direct
-- Supabase REST API or Realtime subscriptions are used.
-- ==============================================================================

-- 1. USERS TABLE
ALTER TABLE users ENABLE ROW LEVEL SECURITY;

-- Allow authenticated users to view their own profile
CREATE POLICY "Users can view own profile"
ON users FOR SELECT
TO authenticated
USING (auth.uid()::text = id::text OR auth.uid()::text = supabase_uid);

-- Allow authenticated users to update their own allowed profile fields
CREATE POLICY "Users can update own profile"
ON users FOR UPDATE
TO authenticated
USING (auth.uid()::text = id::text OR auth.uid()::text = supabase_uid)
WITH CHECK (auth.uid()::text = id::text OR auth.uid()::text = supabase_uid);

-- 2. COMPLAINTS TABLE
ALTER TABLE complaints ENABLE ROW LEVEL SECURITY;

-- Residents can only view their own complaints
CREATE POLICY "Residents view own complaints"
ON complaints FOR SELECT
TO authenticated
USING (
    resident_id::text = auth.uid()::text
    OR EXISTS (
        SELECT 1 FROM users WHERE users.id::text = auth.uid()::text AND users.role IN ('admin', 'official', 'super_admin')
    )
);

-- Approved residents can insert complaints
CREATE POLICY "Residents insert complaints"
ON complaints FOR INSERT
TO authenticated
WITH CHECK (
    resident_id::text = auth.uid()::text
    AND EXISTS (
        SELECT 1 FROM users 
        WHERE users.id::text = auth.uid()::text 
        AND users.account_status = 'approved'
    )
);

-- Admins and designated officials can update complaints
CREATE POLICY "Officials update complaints"
ON complaints FOR UPDATE
TO authenticated
USING (
    EXISTS (
        SELECT 1 FROM users WHERE users.id::text = auth.uid()::text AND users.role IN ('admin', 'official', 'super_admin')
    )
);

-- 3. COMPLAINT ATTACHMENTS TABLE
ALTER TABLE complaint_attachments ENABLE ROW LEVEL SECURITY;

-- Users can view attachments belonging to their own complaints; Admins can view all
CREATE POLICY "View complaint attachments"
ON complaint_attachments FOR SELECT
TO authenticated
USING (
    uploaded_by::text = auth.uid()::text
    OR EXISTS (
        SELECT 1 FROM complaints WHERE complaints.id = complaint_attachments.complaint_id AND complaints.resident_id::text = auth.uid()::text
    )
    OR EXISTS (
        SELECT 1 FROM users WHERE users.id::text = auth.uid()::text AND users.role IN ('admin', 'official', 'super_admin')
    )
);

-- 4. ANNOUNCEMENTS TABLE
ALTER TABLE announcements ENABLE ROW LEVEL SECURITY;

-- Anyone authenticated can view published announcements
CREATE POLICY "View published announcements"
ON announcements FOR SELECT
TO authenticated, anon
USING (publication_status = 'published');

-- Admins can manage all announcements
CREATE POLICY "Admins manage announcements"
ON announcements FOR ALL
TO authenticated
USING (
    EXISTS (
        SELECT 1 FROM users WHERE users.id::text = auth.uid()::text AND users.role IN ('admin', 'official', 'super_admin')
    )
);

-- 5. NOTIFICATIONS TABLE
ALTER TABLE notifications ENABLE ROW LEVEL SECURITY;

-- Recipients can view only their own notifications
CREATE POLICY "Users view own notifications"
ON notifications FOR SELECT
TO authenticated
USING (recipient_id::text = auth.uid()::text);

-- Recipients can update their own notification read status
CREATE POLICY "Users update own notifications"
ON notifications FOR UPDATE
TO authenticated
USING (recipient_id::text = auth.uid()::text)
WITH CHECK (recipient_id::text = auth.uid()::text);

-- 6. CHAT SESSIONS & MESSAGES
ALTER TABLE chat_sessions ENABLE ROW LEVEL SECURITY;
ALTER TABLE chat_messages ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Residents view own chat sessions"
ON chat_sessions FOR ALL
TO authenticated
USING (resident_id::text = auth.uid()::text);

CREATE POLICY "Residents view own chat messages"
ON chat_messages FOR ALL
TO authenticated
USING (
    EXISTS (
        SELECT 1 FROM chat_sessions 
        WHERE chat_sessions.id = chat_messages.session_id 
        AND chat_sessions.resident_id::text = auth.uid()::text
    )
);
