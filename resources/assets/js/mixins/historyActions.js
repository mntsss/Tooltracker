const actionDesc = {
    suspention: {
        in: {
            unconfirmed_return: "Grąžinimas patvirtintas",
            warranty_fix: "Grįžo iš garantinio",
            unwarranted_fix: "Sutaisyta"
        },
        out: {
            unconfirmed_return: "Įšaldyta: Nepatvirtintas grąžinimas",
            warranty_fix: "Įšaldyta: Garantinis taisymas",
            unwarranted_fix: "Įšaldyta: Taisymas"
        }
    },
    withdrawal: {
        in: {
            null: "Įrankis grąžintas"
        },
        out: {
            null: "Įrankis priskirtas vartotojui"
        }
    },
    reservation: {
        in: {
            null: "Įrankio rezervacija patvirtinta"
        },
        out: {
            null: "Įrankis rezervuotas"
        }
    }
};

export default actionDesc;
