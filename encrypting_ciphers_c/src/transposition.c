#include <math.h>
#include <stdio.h>
#include <string.h>

void Encrypt(void) {
	char text[255] = "sifrujemetranspozicnimsystemem";
	char mod[255];
	/*printf("input text\n");
	scanf("%s", text);
	printf("key\n");
	int shiftby;
	scanf("%d", &shiftby);*/

	int shiftby = 4;
	int posun = 0;
	for (int i = 0; i <= strlen(text); i++) {
		int sloupec = strlen(text);
		sloupec = ceil((float)sloupec / (float)shiftby);
		int currpos = (i % sloupec) * shiftby + 1 + (i / sloupec) + posun;
		//strlen(text) / shiftby
		if (text[currpos] == 0) {
			i--;
			posun++;
			continue;
		}
		mod[i] = text[currpos];
		//text[i] += shiftby;
		//while (text[i] > 122) text[i] -= 26;
	}
	printf("%s", mod);
}
void Decrypt(void) {
	char text[255];
	char mod[255];
	printf("input coded text\n");
	scanf("%s", text);
	int shiftby;
	printf("key\n");
	scanf("%d", &shiftby);
	for (int i = 0; i < strlen(text); i++) {
		text[i] -= shiftby;
		while (text[i] < 97) text[i] += 26;
	}
	printf("%s", text);
}

int main(void) {
	char eord[3];
	printf("Encrypt or Dectypt?");
	scanf("%s", eord);
	if ((strcmp("e", eord) == 0) || (strcmp("E", eord) == 0)) {
		Encrypt();
		return 0;
	}
	if ((strcmp("d", eord) == 0) || (strcmp("D", eord) == 0)) {
		Decrypt();
		return 0;
	}
	printf("Only letter e or d are allowed");
	return 0;
}

