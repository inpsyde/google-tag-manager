interface Entity
	extends Readonly< {
		label: string;
		baseUrl: string;
		name: string;
		kind: string;
	} > {}

interface GoogleTagManager
	extends Readonly< {
		Rest: {
			namespace: string;
		};
		Entities: Entity[];
	} > {}

declare let InpsydeGoogleTagManager: GoogleTagManager;
