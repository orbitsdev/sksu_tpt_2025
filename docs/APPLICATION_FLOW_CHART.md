TEXT-BASED FLOWCHART (Easy to Understand)
                ┌────────────────────────────────┐
                │     Student logs into portal    │
                └────────────────────────────────┘
                              │
                              ▼
                ┌────────────────────────────────┐
                │  Student goes to Payment Page   │
                └────────────────────────────────┘
                              │
                              ▼
                ┌────────────────────────────────┐
                │   Student pays at CASHIER       │
                └────────────────────────────────┘
                              │
                              ▼
          ┌────────────────────────────────────────────┐
          │ Cashier inputs payment in system (Filament) │
          └────────────────────────────────────────────┘
                              │
                              ▼
                ┌────────────────────────────────┐
                │ Payment is VERIFIED            │
                │ - verified_by cashier          │
                │ - verified_at set              │
                └────────────────────────────────┘
                              │
                              ▼
        ┌────────────────────────────────────────────────┐
        │ System automatically creates APPLICATION       │
        │ (The official exam permit record)              │
        └────────────────────────────────────────────────┘
                              │
                              ▼
        ┌────────────────────────────────────────────────┐
        │ Student is now REQUIRED to CHOOSE schedule     │
        │ - System shows available slots + capacity      │
        └────────────────────────────────────────────────┘
                              │
                              ▼
        ┌────────────────────────────────────────────────┐
        │ Student selects preferred EXAM SLOT            │
        └────────────────────────────────────────────────┘
                              │
                              ▼
        ┌────────────────────────────────────────────────┐
        │ System automatically assigns:                  │
        │ - examination_slot_id                          │
        │ - examination_room_id                          │
        │ - optional seat_number                         │
        │ Creates application_slot record                │
        └────────────────────────────────────────────────┘
                              │
                              ▼
                ┌────────────────────────────────┐
                │  Permit is COMPLETED           │
                │ Student can now download PDF   │
                └────────────────────────────────┘
