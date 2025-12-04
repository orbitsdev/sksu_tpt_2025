# Examination Slot Design Pattern

## Core Principle: Immutability Once Students Are Assigned

### The Problem
When students apply for examinations, they are assigned to specific:
- **Examination Slot** (date/time/building)
- **Examination Room** (physical room within the slot)
- **Seat Number** (specific seat within the room)

If we allow editing slots after students are assigned, we create serious problems.

---

## Why We DON'T Allow Editing Slots

### 1. **Data Integrity Issues**
```
Example Problem:
- Slot created: 500 capacity, 5 rooms (100 seats each)
- 300 students assigned to rooms 1-3
- Admin tries to change to 3 rooms
→ What happens to students in rooms 4-5?
→ How do we recalculate capacity per room?
→ Seat numbers become invalid
```

### 2. **Student Confusion**
```
Student's perspective:
Day 1: "Your exam is in Science Building, Room 3, Seat 42"
Day 2: Admin edits slot
Day 3: "Your exam is now in Main Building, Room 1, Seat 15"
→ Student already printed permit
→ Student goes to wrong location
→ Chaos on exam day
```

### 3. **Complex Cascading Changes**
```
Changing slot configuration requires:
1. Update ExaminationSlot (total_examinees, number_of_rooms)
2. Recalculate ExaminationRoom capacities
3. Reassign all ApplicationSlot records
4. Update seat numbers
5. Regenerate all permits
6. Notify all affected students
→ Too complex, too risky
```

### 4. **Audit Trail Loss**
- Can't track what changed
- Can't explain why student's assignment changed
- Legal/compliance issues if disputes arise

---

## The Solution: Create New Slots Instead

### Scenario 1: Need More Capacity
**DON'T:** Edit existing slot to increase capacity
**DO:** Create a new slot

```
✅ Good Approach:
- Slot 1: Science Building, Jan 15 → 500 capacity (LOCKED - students assigned)
- Slot 2: Main Building, Jan 15 → 300 capacity (NEW - more capacity)
Total: 800 capacity, no existing students affected
```

### Scenario 2: Wrong Configuration
**DON'T:** Edit the slot
**DO:** Deactivate old, create new

```
✅ Good Approach:
- Slot 1: Wrong building (set is_active = false)
- Slot 2: Correct building (create new)
Students already assigned to Slot 1 remain there
New students apply to Slot 2
```

### Scenario 3: Additional Dates
**DON'T:** Edit existing slot date
**DO:** Create new slot for new date

```
✅ Good Approach:
- Slot 1: Jan 15, 2025 (original)
- Slot 2: Jan 16, 2025 (additional date)
- Slot 3: Jan 17, 2025 (additional date)
Clear separation, no confusion
```

---

## Implementation Details

### Protection Rules

#### Delete Action
```php
DeleteAction::make()
    ->disabled(fn ($record) => $record->hasAssignedStudents())
    ->tooltip(fn ($record) =>
        $record->hasAssignedStudents()
            ? 'Cannot delete: Students are already assigned'
            : null
    )
```

#### Visual Indicators
- **Badge with lock icon** shows assigned student count
- **Warning color** when students are assigned
- **Tooltip** explains why deletion is blocked

#### Helper Methods
```php
// Check if slot has students
public function hasAssignedStudents(): bool
{
    return $this->rooms()->whereHas('applicationSlots')->exists();
}

// Get count of assigned students
public function getAssignedStudentsCountAttribute(): int
{
    return $this->applicationSlots()->count();
}
```

---

## Benefits of This Approach

### ✅ Advantages
1. **Simple Logic** - No complex edit workflows
2. **Data Integrity** - Student assignments never change
3. **Clear Audit Trail** - Each slot is immutable history
4. **No Student Confusion** - Assignment stays constant
5. **Flexibility** - Can add capacity anytime via new slots
6. **Safe** - Can't accidentally break existing assignments

### ❌ Minor Drawbacks
1. **Multiple Slots** - Same exam might have multiple slots
   - *This is actually better for organization*
2. **Can't Delete Old Slots** - Once students assigned
   - *Can deactivate instead with `is_active = false`*

---

## Admin Workflow Examples

### Workflow 1: Setting Up Exam
```
1. Create examination
2. Create slot(s) with appropriate capacity
3. Open applications (is_application_open = true)
4. Students apply and get auto-assigned
5. ✅ Slots are now locked
```

### Workflow 2: Need More Capacity Mid-Registration
```
1. Registration is open
2. Slot 1 is 80% full
3. Admin creates Slot 2 (additional capacity)
4. New students automatically assigned to available rooms
5. ✅ Existing students unaffected
```

### Workflow 3: Wrong Building Configured
```
1. Slot 1 created with wrong building
2. 50 students already assigned
3. Admin:
   - Sets Slot 1 is_active = false (stops new applications)
   - Creates Slot 2 with correct building
   - Leaves Slot 1 students assigned (they take exam there)
4. ✅ New students go to correct building
```

---

## Database Structure

```sql
examinations
  ├── examination_slots (can have many for same exam)
       ├── examination_rooms (auto-generated based on number_of_rooms)
            └── application_slots (students assigned to specific rooms/seats)

Key: Once application_slots exist for a slot → IMMUTABLE
```

---

## Code References

- **Model Logic:** `app/Models/ExaminationSlot.php`
- **Protection:** `app/Filament/Resources/Examinations/Pages/ManageSlot.php`
- **Visual Indicators:** Table column "Assigned Students"

---

## FAQ

**Q: What if I really need to change a slot?**
A: Deactivate it (`is_active = false`) and create a new one. Students in old slot stay there, new students use new slot.

**Q: Can I delete a slot with no students?**
A: Yes! If `assigned_students_count = 0`, delete is allowed.

**Q: What if student wants to change slots?**
A: Handle through application management, not by editing slots.

**Q: How do I add more capacity?**
A: Create a new slot with additional rooms. Don't edit existing slot.

---

## Conclusion

**Remember:** Once students are assigned to a slot, treat it as **immutable**.

Need changes? **Create a new slot** instead of editing existing ones.

This keeps your data clean, students happy, and admins sane.
